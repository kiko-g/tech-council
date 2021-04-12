import pandas
from utils import *
from random import randrange


def users():
    data = pandas.read_csv("mock/csv/user.csv")
    tags = pandas.read_csv("mock/csv/tag.csv")['tag']
    cities = pandas.read_csv("mock/large/cities-large.csv")

    # Bios should be realistic
    for i in range(len(data)):
        place = choose_random(cities)
        interests = choose_n(tags)
        data.loc[i, 'bio'] = "My name is " + data.loc[i, 'name'] + ", " \
                             "I am " + str(randrange(15, 60)) + " years old " \
                             "and I live in " + place[0] + ", " + place[1] + ". " \
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

    data.to_csv("output/user.csv", sep=",", index=False)       # export


def questions():
    return 0
    # data.to_csv("output/questions.csv", sep=",", index=False)   # export
