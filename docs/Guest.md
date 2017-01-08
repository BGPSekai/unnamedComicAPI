# Guest API Reference Guide

1. [Register](#Register)
2. [Auth Login](#AuthLogin)
3. [List Comics](#ListComics)
4. [List Types](#ListTypes)
5. [View Comic Info](#ViewComicInfo)
6. [View Comic Infos](#ViewComicInfos)
7. [View Comic Cover](#ViewComicCover)
8. [View Chapter Page](#ViewChapterPage)
9. [Search Comics](#SearchComics)
10. [View User Info](#ViewUserInfo)
11. [View User Avatar](#ViewUserAvatar)
12. [View User Favorite Comics](#ViewUserFavoriteComics)
13. [List Comments](#ListComments)


## 1. <a name="Register">Register</a>

| Method | URI                | Remark |
|:------:| ------------------ | ------ |
| POST   | /api/auth/register |        |

### Input Parameter

| Type   | Name                  | Required | Remark             |
| ------ | --------------------- |:--------:| ------------------ |
| String | name                  | √        |                    |
| Email  | email                 | √        |                    |
| String | password              | √        | min: 6             |
| String | password_confirmation | √        | same as password   |
| String | from                  |          | for social account |

```
from
  Ex. Google、Facebook
```

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

| Type   | Name     | Required | Remark             |
| ------ | -------- |:--------:| ------------------ |
| Email  | email    | √        |                    | 
| String | password | √        |                    |
| String | from     |          | for social account |

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
      "favorites": *favorites*,
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
      "name": *name*
    },
    "chapters": *chapters*,
    "favorites": *favorites*,
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
      }
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

## 6. <a name="ViewComicInfos">View Comic Infos</a>

| Method | URI             | Remark |
|:------:| --------------- | ------ |
| POST   | /api/comic/info |        |

### Input Parameter

| Type    | Name     | Required | Remark |
| ------- | -------- |:--------:| ------ |
| Integer | comics[] | √        | min: 1 | 

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
      "name": *name*
    },
    "chapters": *chapters*,
    "favorites": *favorites*,
    "created_at": *createTime*,
    "updated_at": *updateTime*",
    "tags": *tags[Array]*
  },
  ...
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

## 7. <a name="ViewComicCover">View Comic Cover</a>

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

## 8. <a name="ViewChapterPage">View Chapter Page</a>

| Method | URI                                    | Remark |
|:------:| -------------------------------------- | ------ |
| GET    | /api/comic/chapter/{chapter_id}/{page} |        |

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
  "message": "Page Not Found"
}
```

## 9. <a name="SearchComics">Search Comics</a>

| Method | URI                                           | Remark          |
|:------:| --------------------------------------------- | --------------- |
| GET    | /api/search/name/{name}/{page}                | Fuzzy           |
| GET    | /api/search/publisher/{user_id}/{page}        |                 |
| GET    | /api/search/type/{id}/{page}                  |                 |
| GET    | /api/search/tag/{name}/{page}?fuzzy={boolean} | Optional: fuzzy |
| GET    | /api/search/author/{name}/{page}              |                 |

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
      "favorites": *favorites*,
      "created_at": *createTime*,
      "updated_at": *updateTime*,
      "tags": *tags[Array]*
    },
    ...(9)
  ],
  "pages": *pages*
}
```

## 10. <a name="ViewUserInfo">View User Info</a>

| Method | URI            | Remark |
|:------:| -------------- | ------ |
| GET    | /api/user/{id} |        |

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
    "avatar": *avatar*,
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

## 11. <a name="ViewUserAvatar">View User Avatar</a>

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

## 12. <a name="ViewUserFavoriteComics">View User Favorite Comics</a>

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

## 13. <a name="ListComments">List Comments</a>

| Method | URI                              | Remark |
|:------:| -------------------------------- | ------ |
| GET    | /api/comment/comic/{id}/{page}   |        |
| GET    | /api/comment/chapter/{id}/{page} |        |

### JSON Response
#### Success
```
Status Code: 200
{
  "status": "success",
  "comments": [
    {
      "id": *id*,
      "comic_id": *comic_id*,
      "chapter_id": *chapter_id*,
      "comment": *comment*,
      "comment_by": {
        "id": *id*,
        "name": *name*,
        "avatar": *avatar*
      },
      "replies": *replies*,
      "created_at": "2016-12-04 12:47:26",
      "updated_at": "2016-12-04 12:47:26"
    },
    ...(9)
  ],
  "pages": *pages*
}
```
