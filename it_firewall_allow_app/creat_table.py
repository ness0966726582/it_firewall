import os
import psycopg2
from dotenv import load_dotenv

# 載入 .env 環境變數
load_dotenv()

# 讀取環境變數
DB_HOST = os.getenv("N_POSTGRES_SERVER")
DB_PORT = os.getenv("N_POSTGRES_PORT")
DB_USER = os.getenv("N_POSTGRES_USER")
DB_PASSWORD = os.getenv("N_POSTGRES_PASSWORD")
DB_NAME = os.getenv("N_POSTGRES_DB")

# 建立資料庫連線
try:
    conn = psycopg2.connect(
        host=DB_HOST,
        port=DB_PORT,
        user=DB_USER,
        password=DB_PASSWORD,
        dbname=DB_NAME
    )
    cur = conn.cursor()

    # 建立 it_firewall_allow 資料表
    create_table_query = """
    CREATE TABLE IF NOT EXISTS it_firewall_allow (
        id SERIAL PRIMARY KEY,
        ticket_number VARCHAR(255) UNIQUE NOT NULL,  -- 申請單號
        request_date DATE NOT NULL,                 -- 申請日期
        request_dept VARCHAR(255) NOT NULL,        -- 申請部門
        request_name VARCHAR(255) NOT NULL,        -- 申請人
        approved_by VARCHAR(255),                  -- 核准人
        source VARCHAR(255),                       -- 來源
        destination VARCHAR(255),                  -- 目標
        protocol VARCHAR(255),                     -- 協議與端口
        start_date DATE,                           -- 有效時間開始
        end_date DATE,                             -- 有效時間結束
        purpose TEXT,                              -- 需求目的及備註
        sd_wan_rules_id VARCHAR(255),             -- SD WAN 規則 ID
        vpn VARCHAR(255),                         -- VPN
        remarks TEXT,                              -- 備註
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- 建立時間
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP   -- 更新時間
    );
    """
    cur.execute(create_table_query)
    conn.commit()
    print("✅ Table 'it_firewall_allow' created or verified successfully!")

    # 檢查並新增欄位（如果不存在）
    alter_table_queries = [
        "ALTER TABLE it_firewall_allow ADD COLUMN IF NOT EXISTS sd_wan_rules_id VARCHAR(255);",
        "ALTER TABLE it_firewall_allow ADD COLUMN IF NOT EXISTS vpn VARCHAR(255);",
        "ALTER TABLE it_firewall_allow ADD COLUMN IF NOT EXISTS remarks TEXT;"
    ]

    for query in alter_table_queries:
        cur.execute(query)
        conn.commit()
        print(f"✅ Column added or already exists: {query.split(' ')[5]}")

except Exception as e:
    print(f"❌ An error occurred: {e}")

finally:
    # 關閉連線
    if cur:
        cur.close()
    if conn:
        conn.close()
