URL:
http://foo.bar/api/<version>/<controller>/<action>?suppress_response_code=1[ &param1=value1... ]

Example:
http://192.168.10.20:83/api/v1/user/get-code?suppress_response_code=1&phone=+78002000600

Headers:
  Content-Type: application/json; charset=UTF-8
  Authorization: Basic <Base64 encoded access token with ":">

All controllers:
  All actions:
    Out:
      Status: 401
        Message:
          "You are requesting with an invalid credential."
      Status: 500
        Message:
          "Внутренняя ошибка сервера"
        Data:
          {<name>: [<message1>, <message2> ...]}
        Description:
          Error during saving model. <name> - name of invalid property. <message1>, <message2> - error messages.

Controller "user":
  Action "get-code":
    In GET:
      Parameters:
        phone=/^\+\d+$/
    Out:
      Status: 200
        Message:
          "Вам отправлено СМС с кодом подтверждения"
      Status: 500
        Message:
          "Ошибка отправки СМС"
      Status: 400
        Message:
          "Неверный формат номера телефона"
  Action "verify-code":
    In GET:
      Parameters:
        phone=/^\+\d+$/
        code=/^\d+$/
    Out:
      Status: 200
        Message:
          "OK"
        Data:
          {"token": <token>}
      Status: 400
        Message:
          "Неверный код"
      Status: 404
        Message:
          "Пользователь не найден"
        Description:
          You'r trying to verify code with wrong phone
  Action "check-token":
    Out:
      Status: 200
        Message:
          "OK"
  Action "reset-token":
    Out:
      Status: 200
        Message:
          "OK"
        Data:
          {"token": <token>}

Controller "profile":
  Action "update":
    In POST:
      Parameters:
        name=/^.*$/
        birth_date=/^\d{4}-\d{2}-\d{2}$/
    Out:
      Status: 200
        Message:
          "OK"
        Data:
          {
            "name": <name>,
            "birth_date": <birth_date>
          }
        Description:
          Returns updated info.
          <birth_date> in "Y-m-d" format.
  Action "view":
    Out:
      Status: 200
        Message:
          "OK"
        Data:
          {
            "name": <name>,
            "birth_date": <birth_date>
          }
        Description:
          Returns updated info.
          <birth_date> in "Y-m-d" format.