import pandas
from utils import *
from random import randrange


def users():
    data = pandas.read_csv("input/users.csv")
    tags = pandas.read_csv("input/static/large/tags.csv")['tag']
    cities = pandas.read_csv("input/static/large/cities.csv")

    # Bios should be realistic
    for i in range(len(data)):
        place = choose_random(cities)
        interests = choose_n(tags)
        data.loc[i, 'bio'] = "My name is " + data.loc[i, 'name'] + ", "\
                             "I am " + str(randrange(15, 60)) + " "\
                             "and I live in " + place[0] + ", " + place[1] + ". "\
                             "I'm interested in " + list_text_formatted(interests)

    # Everyone with over 100 reputation is an expert
    data.loc[data['reputation'] >= 100, ['expert']] = "TRUE"
    data.loc[data['reputation'] < 100, ['expert']] = "FALSE"

    # Around 10% of the users are banned
    for i in range(len(data)):
        r = randrange(100)
        if r < 10:
            data.loc[i, 'banned'] = "TRUE"
        else:
            data.loc[i, 'banned'] = "FALSE"

    data.to_csv("output/users.csv", sep=",", index=False)  # export data to csv
