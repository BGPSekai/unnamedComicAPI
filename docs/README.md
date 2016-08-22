# Comic API Reference Guide

1. [Register](#Register)
2. [Auth Login](#AuthLogin)
3. [Reset Password](#ResetPassword)
4. [Publish Comic](#PublishComic)
5. [Publish Chapter](#PublishChapter)
6. [List Comics](#ListComics)
7. [View User Info](#ViewUserInfo)
8. [View Comic Info](#ViewComicInfo)
9. [View Comic Cover](#ViewComicCover)
10. [View Chapter Page](#ViewChapterPage)
11. [Batch Upload Chapter Pages](#BatchUploadChapterPages)
12. [Search Comics by Name](#SearchComicsByName)
13. [Search Comics by Publisher](#SearchComicsByPublisher)


## 1. <a name="Register">Register</a>

| Method | URI                | Remark |
|:------:| ------------------ | ------ |
| POST   | /api/auth/register |        |

### Input Parameter

| Type   | Name                  | Required | Remark           |
| ------ | --------------------- |:--------:| ---------------- |
| String | name                  | √        |                  |
| Email  | email                 | √        |                  |
| String | password              | √        | min: 6           |
| String | password_confirmation | √        | same as password |

### JSON Response
#### Success
```
Status Code: 200
{
  "status": "success",
  "user": {
    "name": *name*,
    "email": *email*,
    "updated_at": *updateTime*,
    "created_at": *createTime*,
    "id": *id*
  }
}
```

#### Error
```
Status Code: 400
{
  "status": "error",
  "message": *message[Array]*
}
```

## 2. <a name="AuthLogin">Auth Login</a>

| Method | URI       | Remark |
|:------:| --------- | ------ |
| POST   | /api/auth |        |

### Input Parameter

| Type   | Name     | Required | Remark |
| ------ | -------- |:--------:| ------ |
| Email  | email    | √        |        | 
| String | password | √        |        |

### JSON Response
#### Success
```
Status Code: 200
{
  "status": "success",
  "token": *token*
}
```

#### Error
```
Status Code: 401
{
  "status": "error",
  "message": "Invalid Credentials"
}
```

## 3. <a name="ResetPassword">Reset Password</a>

| Method | URI             | Remark   |
|:------:| --------------- | -------- |
| POST   | /api/auth/reset | JWT Auth |

### Input Parameter

| Type   | Name                      | Required | Remark           |
| ------ | ------------------------- |:--------:| ---------------- |
| String | password                  | √        |                  |
| String | new_password              | √        | min: 6           |
| String | new_password_confirmation | √        | same as password |

### JSON Response
#### Success
```
Status Code: 200
{
  "status": "success",
  "user": {
    "id": *id*,
    "name": *name*,
    "email": *email*,
    "created_at": *createTime*,
    "updated_at": *updateTime*
  },
  "token": *token*
}
```

#### Error
```
Status Code: 400
{
  "status": "error",
  "message": *message[Array]*
}
- or -
Status Code: 401
{
  "status": "error",
  "message": "Invalid Credentials"
}
```

## 4. <a name="PublishComic">Publish Comic</a>

| Method | URI          | Remark   |
|:------:| ------------ | -------- |
| POST   | /api/publish | JWT Auth |

### Input Parameter

| Type   | Name    | Required | Remark |
| ------ | ------- |:--------:| ------ |
| String | name    | √        |        |
| String | summary | √        |        |
| File   | cover   | √        | Image  |

### JSON Response
#### Success
```
Status Code : 200
{
  "status": "success",
  "comic": {
  "name": *name*,
  "summary": *summary*,
  "publish_by": *uploadUser*,
  "updated_at": *updateTime*,
  "created_at": *createTime*,
  "id": *id*
  }
}
```

#### Error
```
Status Code: 400
{
  "status": "error",
  "message": *message[Array]*
}
```

## 5. <a name="PublishChapter">Publish Chapter</a>

| Method | URI               | Remark   |
|:------:| ----------------_ | -------- |
| POST   | /api/publish/{id} | JWT Auth |

### Input Parameter

| Type   | Name     | Required | Remark |
| ------ | -------- |:--------:| ------ |
| String | name     | √        |        |
| File   | images[] |          | Image  |

### JSON Response
#### Success
```
Status Code: 200
{
  "status": "success",
  "chapter": {
    "comic_id": *comic_id*,
    "name": *name*,
    "pages": *pages*,
    "publish_by": *uploadUser*,
    "updated_at": *updateTime*,
    "created_at": *createTime*,
    "id": *id*
  }
}
- or -
{
  "status": "success",
  "chapter": {
    "comic_id": *comic_id*,
    "name": *name*,
    "pages": *pages*,
    "publish_by": *uploadUser*,
    "updated_at": *updateTime*,
    "created_at": *createTime*,
    "id": *id*
  }
}
```

#### Error
```
Status Code: 400
{
  "status": "error",
  "message": *message[Array]*
}
- or -
Status Code: 404
{
  "status": "error",
  "message": "Comic Not Found"
}
```

## 6. <a name="ListComics">List Comics</a>

| Method | URI                    | Remark |
|:------:| ---------------------- | ------ |
| GET    | /api/comic/page/{page} |        |

### JSON Response
#### Success
```
Status Code: 200
{
  "status": "success",
  "comics": [
    {
      "id": *id*,
      "name": *name*,
      "summary": *summary*,
      "publish_by": *uploadUser*,
      "chapters": *chapters*,
      "created_at": *createTime*,
      "updated_at": *updateTime*
    },
    ...(9)
  ]
}
```

## 7. <a name="ViewUserInfo">View User Info</a>

| Method | URI            | Remark   |
|:------:| -------------- | -------- |
| GET    | /api/user      | JWT Auth |
| GET    | /api/user/{id} | JWT Auth |

### JSON Response
#### Success
```
Status Code: 200
{
  "status": "success",
  "user": {
    "id": *id*,
    "name": *name*,
    "email": *email*,
    "created_at": *createTime*,
    "updated_at": *updateTime*
  }
}
```

#### Error
```
Status Code: 404
{
  "status": "error",
  "message": "User Not Found"
}
```

## 8. <a name="ViewComicInfo">View Comic Info</a>

| Method | URI             | Remark |
|:------:| --------------- | ------ |
| GET    | /api/comic/{id} |        |

### JSON Response
#### Success
```
{
  "status": "success",
  "comic": {
    "id": *id*,
    "name": *name*,
    "summary": *summary*,
    "publish_by": *uploadUser*,
    "chapters": *chapters*,
    "created_at": *createTime*,
    "updated_at": *updateTime*"
  },
  "chapters": [
    {
      "id": *id*,
      "comic_id": *comicId*,
      "name": *name*,
      "pages": *pages*,
      "created_at": *createTime*,
      "updated_at": *updateTime*,
      "token": *token*
    },
    ...
  ]
}
```

#### Error
```
Status Code: 404
{
  "status": "error",
  "message": "Comic Not Found"
}
```

## 9. <a name="ViewComicCover">View Comic Cover</a>

| Method | URI                   | Remark |
|:------:| --------------------- | ------ |
| GET    | /api/comic/{id}/cover |        |

### Response
#### Success
```
Status Code: 200
*Comic Cover Image*
```

#### Error
```
Status Code: 404
{
  "status": "error",
  "message": "Comic Not Found"
}
```

## 10. <a name="ViewChapterPage">View Chapter Page</a>

| Method | URI                       | Remark |
|:------:| ------------------------- | ------ |
| GET    | /api/comic/chapter/{page} |        |

### Input Parameter

| Type   | Name  | Required | Remark                         |
| ------ | ----- |:--------:| ------------------------------ |
| String | token | √        | JWT token from View Comic Info |

### Response
#### Success
```
Status Code: 200
*Comic Cover Image*
```

#### Error
```
Status Code: 400
{
  "status": "error",
  "message": "A Token Is Required"
}
- or -
Status Code: 401
{
  "status": "error",
  "message": *message[Array]*
}
- or -
Status Code: 404
{
  "status": "error",
  "message": "Page Not Found"
}
```

## 11. <a name="BatchUploadChapterPages">Batch Upload Chapter Pages</a>

| Method | URI                       | Remark   |
|:------:| ------------------------- | -------- |
| POST   | /api/publish/chapter/{id} | JWT Auth |

### Input Parameter

| Type | Name     | Required | Remark |
| ---- | -------- |:--------:| ------ |
| File | images[] | √        | Image  |

### JSON Response
#### Success
```
Status Code: 200
{
  "status": "success",
  "chapter": {
    "id": *id*,
    "comic_id": *comic_id*,
    "name": *name*,
    "pages": *pages*,
    "publish_by": *uploadUser*,
    "created_at": *createTime*,
    "updated_at": *updateTime*
  }
}
```

#### Error
```
Status Code: 400
{
  "status": "error",
  "message": *message[Array]*
}
- or -
Status Code: 404
{
  "status": "error",
  "message": "Chapter Not Found"
}
```

## 12. <a name="SearchComicsByName">Search Comics by Name</a>

| Method | URI                     | Remark |
|:------:| ----------------------- | ------ |
| GET    | /api/name/{name}/{page} |        |

### JSON Response
#### Success
```
Status Code: 200
{
  "status": "success",
  "comics": [
    {
      "id": *id*,
      "name": *name*,
      "summary": *summary*,
      "publish_by": *uploadUser*,
      "chapters": *chapters*,
      "created_at": *createTime*,
      "updated_at": *updateTime*
    },
    ...(9)
  ]
}
```

## 13. <a name="SearchComicsByPublisher">Search Comics by Publisher</a>

| Method | URI                             | Remark |
|:------:| ------------------------------- | ------ |
| GET    | /api/publisher/{user_id}/{page} |        |

### JSON Response
#### Success
```
Status Code: 200
{
  "status": "success",
  "comics": [
    {
      "id": *id*,
      "name": *name*,
      "summary": *summary*,
      "publish_by": *uploadUser*,
      "chapters": *chapters*,
      "created_at": *createTime*,
      "updated_at": *updateTime*
    },
    ...(9)
  ]
}
```
