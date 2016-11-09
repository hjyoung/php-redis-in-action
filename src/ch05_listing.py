import logging
import time
import redis
from datetime import datetime

SEVERITY = {
    logging.DEBUG: 'debug',
    logging.INFO: 'info',
    logging.WARNING: 'warning',
    logging.ERROR: 'error',
    logging.CRITICAL: 'critical'            
}
# 
# value = SEVERITY.copy()
# SEVERITY.update((1, 2) for name in value.values())
# 
# print (SEVERITY)
# # SEVERITY.update((name,name) for name in SEVERITY.values())

def log_recent(conn, name, message, severity=logging.INFO, pipe=None):
    severity = str(SEVERITY.get(severity,severity)).lower()
    destination = 'recent:%s:%s'%(name, severity)
    message = time.asctime() + ' ' + message
    pipe = pipe or conn.pipeline()
    pipe.lpush(destination, message)
    pipe.ltrim(destination,0,99)
    pipe.execute()
    
def log_common(conn, name, message, severity=logging.INFO, timeout=5):
    # 设置日志的级别。
    severity = str(SEVERITY.get(severity, severity)).lower() 
    # 负责存储最新日志的键。
    destination = 'common:%s:%s'%(name, severity)
    # 因为程序每小时需要轮换一次日志，所以它使用一个键来记录当前所处的小时数。
    start_key = destination + ':start'
    pipe = conn.pipeline()
    end = time.time() + timeout
    while time.time() < end:
        try:
            # 对记录当前小时数的键进行监视，确保轮换操作可以正确地执行。
            pipe.watch(start_key)
            # 取得当前时间。
            now = datetime.utcnow().timetuple()
            # 取得当前所处的小时数。
            hour_start = datetime(*now[:4]).isoformat() 
            existing = pipe.get(start_key)
            
            
            # 创建一个事务。
            pipe.multi()
            # 如果目前的常见日志列表是上一个小时的……
            if existing and existing.decode() < hour_start: 
                # ……那么将旧的常见日志信息进行归档。
                pipe.rename(destination, destination + ':last')
                pipe.rename(start_key, destination + ':pstart') 
                # 更新当前所处的小时数。
                pipe.set(start_key, hour_start)

            # 对记录日志出现次数的计数器执行自增操作。
            pipe.zincrby(destination, message) 
            # log_recent()函数负责记录日志并调用execute()函数。
            log_recent(pipe, name, message, severity, pipe) 
            return
        except redis.exceptions.WatchError:
            # 如果程序因为其他客户端在执行归档操作而出现监视错误，那么重试。
            continue
        
conn = redis.Redis("192.168.137.111")
log_common(conn, "hjyang", "12312312", "error")