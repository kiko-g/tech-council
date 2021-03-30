import pandas
from random import randrange


def choose_random(df):
    r = randrange(len(df))                          # pick random entry of data
    return df.loc[r].astype(str).values.tolist()    # make series into list of strings


def choose_n(df):
    n = randrange(1, 5)
    tag_set = []

    for i in range(n):
        r = randrange(len(df))
        tag_set.append(df.loc[r])

    return tag_set


def list_text_formatted(entry_list):
    size = len(entry_list)
    result = ""

    if size == 1:
        return entry_list[0]

    if size == 2:
        return entry_list[0] + " and " + entry_list[1]

    for i in range(size):
        if i != size-1:
            result += entry_list[i] + ", "
        else:
            result = result[:-2]
            result += " and " + entry_list[i]

    return result


def shorten_csv():
    names = pandas.read_csv("input/static/names.csv")
    cities = pandas.read_csv("input/static/cities.csv")

    # shorten cities
    ci = pandas.DataFrame(data={'name': ['Porto', 'Aveiro', 'Braga'],
                                'country': ['Portugal', 'Portugal', 'Portugal']})
    count = len(ci)
    for i in range(0, len(cities) - 1, 15):
        ci.loc[count] = [cities['name'][i], cities['country'][i]]
        count += 1
    ci.to_csv("input/static/short/cities.csv", sep=",", index=False)  # export cities data to shorter csv

    # shorten names
    nm = pandas.DataFrame(data={'name': ['Francisco']})
    count = len(nm)
    for i in range(0, len(names) - 1, 50):
        nm.loc[count] = names['name'][i]
        count += 1

    nm.to_csv("input/static/short/names.csv", sep=",", index=False)  # export names data to shorter csv
