# unnamedComicAPI
暫未命名的漫畫API

## 首頁

URL | 頁面 | 其他
--- | --- | --- |
/ | 首頁 |
[auth](#Auth) | JWT 認證 |
[service](#Service) | 服務 |

### <a name="Auth"></a> JWT 認證
URL | 頁面 | 其他
--- | --- | --- |
/auth | JWT | POST

>`/auth`

類型 | 參數名稱 | 必須
--- | --- | --- |
String | email | ✔
String | password | ✔

>success

```
{
    "token": *token*
}
```

>error

```
{
    "error": "invalid_credentials"
}
```

-----or-----

```
{
    "error": "could_not_create_token"
}
```

### <a name="Service"></a>Service 服務
URL | 頁面 | 其他
--- | --- | --- |
/service/register | 註冊帳號 | POST

>`/service/register`

類型 | 參數名稱 | 必須
--- | --- | --- |
String | email | ✔
String | password | ✔
String | password_confirmation | ✔
String | name | ✔

>success

```
{
    "status": "success",
    "msg": "Register successful."
}
```

>error

```
{
    "status": "error",
    "msg": *msg[Array]*
}
```
