# quiz-app-back
1- launch xamp, create new database (quiz-app) and run the schema provided<br>
2-clone all files into xamp/htdocs directory<br>
3-visit localhost/quiz-app in browser to test connection<br>

to test apis run these in bash terminal:<br>
register:<br>
curl -X POST http://localhost/quiz-backend/register <br>
-H "Content-Type: application/json" <br>
-d '{"name":"Test User","email":"test@example.com","password":"test123"}' <br>

login: <br>
curl -X POST http://localhost/quiz-backend/login <br>
-H "Content-Type: application/json" <br>
-d '{"email":"test@example.com","password":"test123"}' <br>

create quiz:<br>
curl -X POST http://localhost/quiz-backend/quizzes <br>
-H "Content-Type: application/json" <br>
-d '{"title":"Science Quiz","description":"Test your science knowledge","created_by":1}' <br>

get all quizzes: <br>
curl -X GET http://localhost/quiz-backend/quizzes <br>

update quiz: <br>
curl -X PUT http://localhost/quiz-backend/quizzes <br>
-H "Content-Type: application/json" <br>
-d '{"quiz_id":1,"title":"Updated Quiz Title","description":"New description"}' <br>

delete quiz: <br>
curl -X DELETE http://localhost/quiz-backend/quizzes/1 <br>

