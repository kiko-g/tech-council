import pandas
from random import randrange


# utils
def choose_random(df):
    r = randrange(len(df))                          # pick random entry of data
    return df.loc[r].astype(str).values.tolist()    # make series into list of strings


def users():
    data = pandas.read_csv("input/users.csv")
    names = pandas.read_csv("input/static/large-names.csv")
    cities = pandas.read_csv("input/static/large-cities.csv")

    # Bios should be realistic
    for i in range(len(data)):
        place = choose_random(cities)
        biography = "My name is " + data.loc[i, 'name'] + ", I live in " + place[0] + ", " + place[1]
        data.loc[i, 'bio'] = biography

    # Everyone with over 100 reputation is an expert
    data.loc[data['reputation'] >= 100, ['expert']] = "TRUE"
    data.loc[data['reputation'] < 100, ['expert']] = "FALSE"

    # Around 10% of the users are banned
    for i in range(len(data)):
        r = randrange(100)
        if r < 10:
            data.loc[i, 'banned'] = 'TRUE'
        else:
            data.loc[i, 'banned'] = 'FALSE'

    # export data to csv
    data.to_csv("output/users.csv", sep=",", index=False)


# Main
if __name__ == '__main__':
    users()
