# quiz-app-back
1- launch xamp, create new database (quiz-app) and run the schema provided<br>
2-clone all files into xamp/htdocs directory<br>
3-visit localhost/quiz-app in browser to test connection<br>

to test apis run these in bash terminal:<br>
register:<br>
curl -X POST http://localhost/quiz-backend/register \<br>
-H "Content-Type: application/json" \<br>
-d '{"name":"Test User","email":"test@example.com","password":"test123"}'<br>

