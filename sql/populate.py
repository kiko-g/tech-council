import pandas as pd

if __name__ == '__main__':
    # parse csv data from xlsx
    excel_file = pd.ExcelFile('database.xlsx')
    tag = pd.read_excel(excel_file, 'tag')
    photo = pd.read_excel(excel_file, 'photo')
    user = pd.read_excel(excel_file, 'user')
    content = pd.read_excel(excel_file, 'content')
    question = pd.read_excel(excel_file, 'question')
    answer = pd.read_excel(excel_file, 'answer')
    answer_comment = pd.read_excel(excel_file, 'answer_comment')
    question_comment = pd.read_excel(excel_file, 'question_comment')
    moderator = pd.read_excel(excel_file, 'moderator')
    notification = pd.read_excel(excel_file, 'notification')
    ban = pd.read_excel(excel_file, 'ban')
    report = pd.read_excel(excel_file, 'report')
    user_report = pd.read_excel(excel_file, 'user_report')
    content_report = pd.read_excel(excel_file, 'content_report')
    follow_tag = pd.read_excel(excel_file, 'follow_tag')
    user_vote_question = pd.read_excel(excel_file, 'user_vote_question')
    user_vote_answer = pd.read_excel(excel_file, 'user_vote_answer')
    saved_question = pd.read_excel(excel_file, 'saved_question')
    question_tag = pd.read_excel(excel_file, 'question_tag')
    
    # create script
    populate_sql = open("populate.sql", "w")
    
    # photo table strings
    populate_string = "-- PHOTO TABLE\n"
    for i in range(len(photo)):
        populate_string += "INSERT INTO photo(path) VALUES ("
        populate_string += "'"
        populate_string += str(photo.loc[i, 'path'])
        populate_string += "');\n"
    
    # user table strings
    populate_string += "\n\n\n-- USER TABLE\n"
    for i in range(len(user)):
        populate_string += "INSERT INTO \"user\"(email,\"name\",\"password\",join_date,reputation,bio,banned,expert,profile_photo) VALUES ("
        populate_string += "'"
        populate_string += str(user.loc[i, 'email'])
        populate_string += "','"
        populate_string += str(user.loc[i, 'name'])
        populate_string += "','"
        populate_string += str(user.loc[i, 'password'])
        populate_string += "','"
        populate_string += str(user.loc[i, 'join_date'])
        populate_string += "','"
        populate_string += str(user.loc[i, 'reputation'])
        populate_string += "','"
        populate_string += str(user.loc[i, 'bio'])
        populate_string += "','"
        populate_string += str(user.loc[i, 'banned'])
        populate_string += "','"
        populate_string += str(user.loc[i, 'expert'])
        populate_string += "',"
        populate_string += str(user.loc[i, 'profile_photo'])
        populate_string += ");\n"

    # tag table strings
    populate_string += "\n\n\n-- TAG TABLE\n"
    for i in range(len(tag)):
        populate_string += "INSERT INTO tag(\"name\",\"description\",author_id) VALUES ("
        populate_string += "'"
        populate_string += str(tag.loc[i, 'name'])
        populate_string += "','"
        populate_string += str(tag.loc[i, 'description'])
        populate_string += "','"
        populate_string += str(tag.loc[i, 'author_id'])
        populate_string += "');\n"
    
    # content table strings
    populate_string += "\n\n\n-- CONTENT TABLE\n"
    for i in range(len(content)):
        populate_string += "INSERT INTO content(main,creation_date,modification_date,author_id) VALUES ("
        populate_string += "'"
        populate_string += str(content.loc[i, 'main'])
        populate_string += "','"
        populate_string += str(content.loc[i, 'creation_date'])
        populate_string += "',"
        if(str(content.loc[i, 'modification_date']) == 'NaT'):
            populate_string += "NULL"
            populate_string += ","
        else:
            populate_string += "'"
            populate_string += str(content.loc[i, 'modification_date'])
            populate_string += "',"
        populate_string += str(content.loc[i, 'author_id'])
        populate_string += ");\n"
    
    
    # question table strings
    populate_string += "\n\n\n-- QUESTION TABLE\n"
    for i in range(len(question)):
        populate_string += "INSERT INTO question(content_id,title,votes_difference) VALUES ("
        populate_string += str(question.loc[i, 'content_id'])
        populate_string += ",'"
        populate_string += str(question.loc[i, 'title'])
        populate_string += "',"
        populate_string += str(question.loc[i, 'votes_difference'])
        populate_string += ");\n"


    # answer table strings
    populate_string += "\n\n\n-- ANSWER TABLE\n"
    for i in range(len(answer)):
        populate_string += "INSERT INTO answer(content_id,votes_difference,question_id) VALUES ("
        populate_string += str(answer.loc[i, 'content_id'])
        populate_string += ","
        populate_string += str(answer.loc[i, 'votes_difference'])
        populate_string += ","
        populate_string += str(answer.loc[i, 'question_id'])
        populate_string += ");\n"


    # answer comments table strings
    populate_string += "\n\n\n-- ANSWER COMMENT TABLE\n"
    for i in range(len(answer_comment)):
        populate_string += "INSERT INTO answer_comment(content_id,answer_id) VALUES ("
        populate_string += str(answer_comment.loc[i, 'content_id'])
        populate_string += ","
        populate_string += str(answer_comment.loc[i, 'answer_id'])
        populate_string += ");\n"


    # question comments table strings
    populate_string += "\n\n\n-- QUESTION COMMENT TABLE\n"
    for i in range(len(question_comment)):
        populate_string += "INSERT INTO question_comment(content_id,question_id) VALUES ("
        populate_string += str(question_comment.loc[i, 'content_id'])
        populate_string += ","
        populate_string += str(question_comment.loc[i, 'question_id'])
        populate_string += ");\n"


    # moderator table strings
    populate_string += "\n\n\n-- MODERATOR TABLE\n"
    for i in range(len(moderator)):
        populate_string += "INSERT INTO moderator(\"user_id\",questions_deleted,answers_deleted,comments_deleted,banned_users,solved_reports) VALUES ("
        populate_string += str(moderator.loc[i, 'user_id'])
        populate_string += ","
        populate_string += str(moderator.loc[i, 'questions_deleted'])
        populate_string += ","
        populate_string += str(moderator.loc[i, 'answers_deleted'])
        populate_string += ","
        populate_string += str(moderator.loc[i, 'comments_deleted'])
        populate_string += ","
        populate_string += str(moderator.loc[i, 'banned_users'])
        populate_string += ","
        populate_string += str(moderator.loc[i, 'solved_reports'])
        populate_string += ");\n"
    
    
    # notification table strings
    populate_string += "\n\n\n-- NOTIFICATION TABLE\n"
    for i in range(len(notification)):
        populate_string += "INSERT INTO notification(type,content,icon,\"date\",\"user_id\") VALUES ("
        populate_string += "'"
        populate_string += str(notification.loc[i, 'type'])
        populate_string += "','"
        populate_string += str(notification.loc[i, 'content'])
        populate_string += "','"
        populate_string += str(notification.loc[i, 'icon'])
        populate_string += "','"
        populate_string += str(notification.loc[i, 'date'])
        populate_string += "',"
        populate_string += str(notification.loc[i, 'user_id'])
        populate_string += ");\n"


    # ban table strings
    populate_string += "\n\n\n-- BAN TABLE\n"
    for i in range(len(ban)):
        populate_string += "INSERT INTO ban(\"start\",\"end\",reason,\"user_id\", moderator_id) VALUES ("
        populate_string += "'"
        populate_string += str(ban.loc[i, 'start'])
        populate_string += "','"
        populate_string += str(ban.loc[i, 'end'])
        populate_string += "','"
        populate_string += str(ban.loc[i, 'reason'])
        populate_string += "',"
        populate_string += str(ban.loc[i, 'user_id'])
        populate_string += ","
        populate_string += str(ban.loc[i, 'moderator_id'])        
        populate_string += ");\n"
        

    # report table strings
    populate_string += "\n\n\n-- REPORT TABLE\n"
    for i in range(len(report)):
        populate_string += "INSERT INTO report(\"description\",solved,reporter_id,solver_id) VALUES ("
        populate_string += "'"
        populate_string += str(report.loc[i, 'description'])
        populate_string += "','"
        populate_string += str(report.loc[i, 'solved'])
        populate_string += "',"
        populate_string += str(report.loc[i, 'reporter_id'])
        populate_string += ","
        populate_string += str(report.loc[i, 'solver_id'])        
        populate_string += ");\n"


    # user report table strings
    populate_string += "\n\n\n-- USER REPORT TABLE\n"
    for i in range(len(user_report)):
        populate_string += "INSERT INTO user_report(report_id,\"user_id\") VALUES ("
        populate_string += str(user_report.loc[i, 'report_id'])
        populate_string += ","
        populate_string += str(user_report.loc[i, 'user_id'])
        populate_string += ");\n"
        
    # content report table strings
    populate_string += "\n\n\n-- CONTENT REPORT TABLE\n"
    for i in range(len(content_report)):
        populate_string += "INSERT INTO content_report(report_id,content_id) VALUES ("
        populate_string += str(content_report.loc[i, 'report_id'])
        populate_string += ","
        populate_string += str(content_report.loc[i, 'content_id'])
        populate_string += ");\n"
        

    # follow tag table strings
    populate_string += "\n\n\n-- FOLLOW TAG TABLE\n"
    for i in range(len(follow_tag)):
        populate_string += "INSERT INTO follow_tag(tag_id,\"user_id\") VALUES ("
        populate_string += str(follow_tag.loc[i, 'tag_id'])
        populate_string += ","
        populate_string += str(follow_tag.loc[i, 'user_id'])
        populate_string += ");\n"
        

    # user vote question table strings
    populate_string += "\n\n\n-- USER VOTE QUESTION TABLE\n"
    for i in range(len(user_vote_question)):
        populate_string += "INSERT INTO user_vote_question(\"user_id\",question_id,vote) VALUES ("
        populate_string += str(user_vote_question.loc[i, 'user_id'])
        populate_string += ","
        populate_string += str(user_vote_question.loc[i, 'question_id'])
        populate_string += ","
        populate_string += str(user_vote_question.loc[i, 'vote'])
        populate_string += ");\n"
        
    # user vote answer table strings
    populate_string += "\n\n\n-- USER VOTE ANSWER TABLE\n"
    for i in range(len(saved_question)):
        populate_string += "INSERT INTO user_vote_answer(\"user_id\",answer_id,vote) VALUES ("
        populate_string += str(user_vote_answer.loc[i, 'user_id'])
        populate_string += ","
        populate_string += str(user_vote_answer.loc[i, 'answer_id'])
        populate_string += ","
        populate_string += str(user_vote_answer.loc[i, 'vote'])
        populate_string += ");\n"
        
        
    # saved questions table strings
    populate_string += "\n\n\n-- SAVED QUESTION TABLE\n"
    for i in range(len(saved_question)):
        populate_string += "INSERT INTO saved_question(\"user_id\",question_id) VALUES ("
        populate_string += str(saved_question.loc[i, 'user_id'])
        populate_string += ","
        populate_string += str(saved_question.loc[i, 'question_id'])
        populate_string += ");\n"


    # question tag table strings
    populate_string += "\n\n\n-- QUESTION TAG TABLE\n"
    for i in range(len(question_tag)):
        populate_string += "INSERT INTO question_tag(question_id,tag_id) VALUES ("
        populate_string += str(question_tag.loc[i, 'question_id'])
        populate_string += ","
        populate_string += str(question_tag.loc[i, 'tag_id'])
        populate_string += ");\n"


    populate_sql.write(populate_string) # write to file
