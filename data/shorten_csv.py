import pandas

if __name__ == '__main__':
    names = pandas.read_csv("input/static/large-names.csv")
    cities = pandas.read_csv("input/static/large-cities.csv")

    # shorten cities
    ci = pandas.DataFrame(data={'name': ['Porto', 'Aveiro', 'Braga'],
                                'country': ['Portugal', 'Portugal', 'Portugal']})
    count = len(ci)
    for i in range(0, len(cities) - 1, 15):
        ci.loc[count] = [cities['name'][i], cities['country'][i]]
        count += 1
    ci.to_csv("input/static/cities.csv", sep=",", index=False)

    # shorten names
    nm = pandas.DataFrame(data={'name': ['Francisco']})
    count = len(nm)
    for i in range(0, len(names) - 1, 50):
        nm.loc[count] = names['name'][i]
        count += 1
    nm.to_csv("input/static/names.csv", sep=",", index=False)
