from flask import Flask, jsonify
import pandas as pd
import numpy as np
from sklearn.cluster import KMeans
from sklearn.preprocessing import StandardScaler
import mysql.connector

app = Flask(__name__)

# ==========================================
# CẤU HÌNH THAM SỐ NGHIỆP VỤ (HYBRID MODEL)
# ==========================================
# Tầng 1: Luật cứng (Ngưỡng đỏ lập tức)
MAX_DEVICES = 3
MAX_WATCH_TIME = 800

# Tầng 2: Ngưỡng bất thường của K-Means
# Nếu khoảng cách tới nhóm bình thường > 2.0 -> Gắn nhãn Nghi ngờ (Cam)
KMEANS_THRESHOLD = 2.0 

# Cấu hình Database
DB_CONFIG = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'elearning'
}

@app.route('/api/check-fraud', methods=['GET'])
def check_fraud():
    try:
        # 1. KẾT NỐI CƠ SỞ DỮ LIỆU & LẤY DỮ LIỆU
        conn = mysql.connector.connect(**DB_CONFIG)
        query = "SELECT id, name, device_count, watch_time FROM users WHERE role = 'user'"
        df = pd.read_sql(query, conn)
        conn.close()

        if df.empty:
            return jsonify({"status": "error", "message": "Không có dữ liệu học viên"})

        # Tạo danh sách chứa kết quả cuối cùng
        results = []

        # ==========================================
        # TẦNG 1: BỘ LỌC LUẬT CỨNG (RULE-BASED)
        # ==========================================
        cond_hard_rule = (df['device_count'] > MAX_DEVICES) | (df['watch_time'] > MAX_WATCH_TIME)
        rule_violators = df[cond_hard_rule]
        
        for _, row in rule_violators.iterrows():
            results.append({
                "id": int(row['id']),
                "name": row['name'],
                "device_count": int(row['device_count']),
                "watch_time": int(row['watch_time']),
                "status": "Vi phạm quy định (Luật cứng)",
                "color": "red"
            })

        # Những người chưa bị dán nhãn đỏ
        df_remaining = df[~cond_hard_rule].copy()

        # ==========================================
        # TẦNG 1.5: LUẬT MIỄN TRỪ (PROTECTION LAYER)
        # ==========================================
        # Nhóm 1: Chưa tương tác (0 phút) -> Màu xám
        cond_zero = df_remaining['watch_time'] == 0
        zero_users = df_remaining[cond_zero]
        for _, row in zero_users.iterrows():
            results.append({
                "id": int(row['id']), "name": row['name'],
                "device_count": int(row['device_count']), "watch_time": int(row['watch_time']),
                "status": "Chưa tương tác", "color": "gray"
            })

        # Nhóm 2: Mới học (1-14 phút và <= 2 thiết bị) -> Màu xanh (Minh oan cho Ngọc Anh)
        cond_newbie = (df_remaining['watch_time'] > 0) & (df_remaining['watch_time'] < 15) & (df_remaining['device_count'] <= 2)
        newbie_users = df_remaining[cond_newbie]
        for _, row in newbie_users.iterrows():
            results.append({
                "id": int(row['id']), "name": row['name'],
                "device_count": int(row['device_count']), "watch_time": int(row['watch_time']),
                "status": "Bình thường", "color": "green"
            })

        # ==========================================
        # TẦNG 2: THUẬT TOÁN K-MEANS NÂNG CẤP
        # ==========================================
        # Chỉ những ai thực sự tham gia học mới bị AI phân tích hành vi
        cond_kmeans = ~(cond_zero | cond_newbie)
        valid_users = df_remaining[cond_kmeans].copy()

        if not valid_users.empty and len(valid_users) >= 2:
            # Bước 2.1: Chuẩn hóa dữ liệu
            scaler = StandardScaler()
            features = valid_users[['device_count', 'watch_time']]
            scaled_features = scaler.fit_transform(features)

            # Bước 2.2: Huấn luyện K-Means chia 2 cụm
            kmeans = KMeans(n_clusters=2, init='k-means++', random_state=42, n_init=10)
            kmeans.fit(scaled_features)

            # Bước 2.3: KỸ THUẬT MAIN CENTROID (Tóm gọn Test 48)
            # Tìm cụm có đông học viên nhất (Đại diện cho nhóm bình thường)
            counts = np.bincount(kmeans.labels_)
            main_cluster_label = np.argmax(counts) 
            main_centroid = kmeans.cluster_centers_[main_cluster_label] 

            distances = []
            for i in range(len(scaled_features)):
                point = scaled_features[i]
                # Tính khoảng cách của MỌI người tới tâm của nhóm Bình Thường
                dist = np.linalg.norm(point - main_centroid)
                distances.append(dist)
            
            valid_users['distance'] = distances

            # Bước 2.4: Gắn nhãn dựa trên khoảng cách tới chuẩn bình thường
            for _, row in valid_users.iterrows():
                if row['distance'] > KMEANS_THRESHOLD:
                    status_text = "Nghi ngờ (K-Means phát hiện)"
                    color = "orange"
                else:
                    status_text = "Bình thường"
                    color = "green"

                results.append({
                    "id": int(row['id']),
                    "name": row['name'],
                    "device_count": int(row['device_count']),
                    "watch_time": int(row['watch_time']),
                    "status": status_text,
                    "color": color,
                    "distance": round(row['distance'], 2)
                })
        
        elif not valid_users.empty and len(valid_users) == 1:
            # Xử lý nếu chỉ có 1 người duy nhất lọt vào lớp AI
            row = valid_users.iloc[0]
            results.append({
                "id": int(row['id']), "name": row['name'],
                "device_count": int(row['device_count']), "watch_time": int(row['watch_time']),
                "status": "Bình thường", "color": "green"
            })

        # 3. TRẢ KẾT QUẢ JSON
        return jsonify({
            "status": "success",
            "total_analyzed": len(df),
            "data": results
        })

    except Exception as e:
        return jsonify({"status": "error", "message": str(e)})

if __name__ == '__main__':
    # Quan trọng: Chuyển Unikey sang tiếng Anh và chạy lệnh: python api_ai.py
    app.run(debug=True, port=5000)