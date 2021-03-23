import pandas as pd 
from random import randrange

data = pd.read_csv("users.csv")

for i in data["reputation"]:
    if i > 100:
        data["expert"] = "TRUE"
    else:
        data["expert"] = "FALSE"
        
for i in data["banned"]:
    r = randrange(100)
    if r < 3:
        data["banned"] = "TRUE"
    else:
        data["banned"] = "FALSE"
        
print(data)