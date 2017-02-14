# Guest API Reference Guide

1. [Register](#Register)
2. [Auth Login](#AuthLogin)
3. [List Comics](#ListComics)
4. [List Types](#ListTypes)
5. [View Comic Info](#ViewComicInfo)
6. [View Comic Cover](#ViewComicCover)
7. [View Chapter Page](#ViewChapterPage)
8. [Search Comics](#SearchComics)
9. [View User Info](#ViewUserInfo)
10. [View User Avatar](#ViewUserAvatar)
11. [View User Favorite Comics](#ViewUserFavoriteComics)
12. [List Comments](#ListComments)


```
timestamp: created_at, updated_at
```

## 1. <a name="Register">Register</a>

| Method | URI                | Remark |
|:------:| ------------------ | ------ |
| POST   | /api/auth/register |        |

### Input Parameter

| Type   | Name                  | Required | Remark             |
| ------ | --------------------- |:--------:| ------------------ |
| String | name                  | √        |                    |
| Email  | email                 | √        | unique             |
| String | password              | √        | min: 6             |
| String | password_confirmation | √        | same as password   |


### JSON Response
#### Success
```
Status Code: 200
{
  "status": "success",
  "user": {
    "id": *id*
    "name": *name*,
    "email": *email*
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

---

#### For Social Account

| Type   | Name     | Required | Remark             |
| ------ | -------- |:--------:| ------------------ |
| String | name     | √        |                    |
| Email  | email    | √        |                    | 
| String | password | √        |                    |
| String | from     | √        |                    |


```
from
  Ex. Google, ...
```

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
Status Code: 400
{
  "status": "error",
  "message": *message[Array]*
}
- or -
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
      "types": *types[Array]*,
      "published_by": {
        "id": *id*,
        "name": *name*
      },
      "chapter_count": *chapter_count*,
      "favorite_count": *favorite_count*
      "tags": *tags[Array]*
    },
    ...(19)
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
    "types": *types[Array]*,
    "published_by": {
      "id": *id*,
      "name": *name*
    },
    "chapter_count": *chapter_count*,
    "favorite_count": *favorite_count*,
    "tags": *tags[Array]*,
    "chapters": [
      {
        "id": *id*,
        "name": *name*,
        "pages": *page*,
        "published_by": *published_by[Array]*
      }
    ]
  }
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

| Method | URI                                    | Remark |
|:------:| -------------------------------------- | ------ |
| GET    | /api/comic/chapter/{chapter_id}/{page} |        |

### Response
#### Success
```
Status Code: 200
*Chapter Page Image*
```

#### Error
```
Status Code: 404
{
  "status": "error",
  "message": "Page Not Found"
}
```

## 8. <a name="SearchComics">Search Comics</a>

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
  "comics": *comics[Array](20)*,
  "pages": *pages*
}
```

## 9. <a name="ViewUserInfo">View User Info</a>

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
    "from": *from*,
    "sex": *sex*,
    "birthday": *birthday*,
    "location": *location*,
    "sign": *sign*,
    "blocked_until": *unblockedTime*,
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

## 10. <a name="ViewUserAvatar">View User Avatar</a>

| Method | URI                                   | Remark |
|:------:| ------------------------------------- | ------ |
| GET    | /public/users/{user_id}.{user_avatar} |        |

Ex. /users/1.png

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

## 11. <a name="ViewUserFavoriteComics">View User Favorite Comics</a>

| Method | URI                       | Remark |
|:------:| ------------------------- | ------ |
| GET    | /api/user/{id}/favorites  |        |

### JSON Response
#### Success
```
Status Code: 200
{
  "status": "success",
  "favorites": *comics[Array]*
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

## 12. <a name="ListComments">List Comments</a>

| Method | URI                              | Remark |
|:------:| -------------------------------- | ------ |
| GET    | /api/comment/comic/{id}/{page}   |        |
| GET    | /api/comment/chapter/{id}/{page} |        |

```
order by created_at
```

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
      "reply_count": *replies*,
    },
    ...(19)
  ],
  "pages": *pages*
}
```
