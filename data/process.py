import pandas
from random import randrange


def users():
    data = pandas.read_csv("input/users.csv")
    size = len(data)

    # Everyone with over 100 reputation is an expert
    data.loc[data['reputation'] >= 100, ['expert']] = "TRUE"
    data.loc[data['reputation'] < 100, ['expert']] = "FALSE"

    # Around 10% of the users are banned
    # count = 0
    for i in range(size):
        r = randrange(100)
        if r < 10:
            # count += 1
            data.loc[i, 'banned'] = 'TRUE'
        else:
            data.loc[i, 'banned'] = 'FALSE'

    # print(str(count * 100 / size) + "%")
    data.to_csv("output/users.csv", sep=",", index=False)


# Main
if __name__ == '__main__':
    users()
