# Guest API Reference Guide

1. [Register](#Register)
2. [Auth Login](#AuthLogin)
3. [List Comics](#ListComics)
4. [List Types](#ListTypes)
5. [View Comic Info](#ViewComicInfo)
6. [View Comic Cover](#ViewComicCover)
7. [View Chapter Page](#ViewChapterPage)
8. [Search Comics](#SearchComics)
9. [View User Avatar](#ViewUserAvatar)
10. [View User Favorite Comics](#ViewUserFavoriteComics)


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
    "avatar": "",
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

## 3. <a name="ListComics">List Comics</a>

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
      "author": *author*,
      "type": {
        "id": *id*,
        "name": *name*
      },
      "publish_by": {
        "id": *id*,
        "name": *name*
      },
      "chapters": *chapters*,
      "created_at": *createTime*,
      "updated_at": *updateTime*,
      "tags": *tags[Array]*
    },
    ...(9)
  ],
  "pages": *pages*
}
```

## 4. <a name="ListTypes">List Types</a>

| Method | URI       | Remark |
|:------:| --------- | ------ |
| GET    | /api/type |        |

### JSON Response
#### Success
```
Status Code: 200
{
  "status": "success",
  "types": [
    {
      "id": *id*,
      "name": *name*
    },
    ...
  ]
}
```

## 5. <a name="ViewComicInfo">View Comic Info</a>

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
    "author": *author*,
    "type": {
      "id": *id*,
      "name": *name*
    },
    "publish_by": {
      "id": *id*,
      "name": *name*,
      "email": *email*
    },
    "chapters": *chapters*,
    "created_at": *createTime*,
    "updated_at": *updateTime*",
    "tags": *tags[Array]*
  },
  "chapters": [
    {
      "id": *id*,
      "comic_id": *comicId*,
      "name": *name*,
      "pages": *pages*,
      "created_at": *createTime*,
      "updated_at": *updateTime*,
      "publish_by": {
        "id": *id*,
        "name": *name*
      },
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

## 6. <a name="ViewComicCover">View Comic Cover</a>

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

## 7. <a name="ViewChapterPage">View Chapter Page</a>

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

## 8. <a name="SearchComics">Search Comics</a>

| Method | URI                                      | Remark |
|:------:| ---------------------------------------- | ------ |
| GET    | /api/search/name/{name}/{page}           | Fuzzy  |
| GET    | /api/search/publisher/{user_id}/{page}   |        |
| GET    | /api/search/type/{id}/{page}             |        |
| GET    | /api/search/tag/{name}/{page}            |        |
| GET    | /api/search/author/{name}/{page}         |        |

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
      "author": *author*,
      "type": {
        "id": *id*,
        "name": *name*
      },
      "publish_by": {
        "id": *id*,
        "name": *name*
      },
      "chapters": *chapters*,
      "created_at": *createTime*,
      "updated_at": *updateTime*,
      "tags": *tags[Array]*
    },
    ...(9)
  ],
  "pages": *pages*
}
```

## 9. <a name="ViewUserAvatar">View User Avatar</a>

| Method | URI                                   | Remark |
|:------:| ------------------------------------- | ------ |
| GET    | /public/users/{user_id}.{user_avatar} |        |

Ex. /users/1.jpg

### Response
#### Success
```
Status Code: 200
*User Avatar Image*
```

#### Error
```
Status Code: 404
```

## 10. <a name="ViewUserFavoriteComics">View User Favorite Comics</a>

| Method | URI                       | Remark |
|:------:| ------------------------- | ------ |
| GET    | /api/user/{id}/favorites  |        |

### JSON Response
#### Success
```
Status Code: 200
{
  "status": "success",
  "favorites": *favorites[Array]*
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
