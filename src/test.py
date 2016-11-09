import redis
import pprint

conn = redis.Redis("192.168.137.111")
conn.set("hjyang","haijun2")
print (conn.get("hjyang"))