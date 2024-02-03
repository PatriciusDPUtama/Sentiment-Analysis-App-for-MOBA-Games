import mysql.connector as connection
import pandas as pd

mydb = connection.connect(host="localhost", database = 'ta160420121',user="root", passwd="")
query = "Select * from trainingdata;"
df = pd.read_sql(query,mydb)
mydb.close()
