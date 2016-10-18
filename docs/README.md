# Comic API Reference Guide

1. [Register](#Register)
2. [Auth Login](#AuthLogin)
3. [Reset Password](#ResetPassword)
4. [Publish Comic](#PublishComic)
5. [Publish Chapter](#PublishChapter)
6. [Tag Comic](#TagComic)
7. [Untag Comic](#UntagComic)
8. [List Comics](#ListComics)
9. [List Types](#ListTypes)
10. [View User Info](#ViewUserInfo)
11. [View Comic Info](#ViewComicInfo)
12. [View Comic Cover](#ViewComicCover)
13. [View Chapter Page](#ViewChapterPage)
14. [Batch Upload Chapter Pages](#BatchUploadChapterPages)
15. [Search Comics](#SearchComics)
16. [Update User Avatar](#UpdateUserAvatar)
17. [View User Avatar](#ViewUserAvatar)
18. [Add User Favorite Comic](#AddUserFavoriteComic)
19. [Delete User Favorite Comic](#DeleteUserFavoriteComic)
20. [View User Favorite Comics](#ViewUserFavoriteComics)


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
    "avatar": *avatar*,
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

| Type    | Name    | Required | Remark |
| ------- | ------- |:--------:| ------ |
| String  | name    | √        |        |
| String  | summary | √        |        |
| String  | author  | √        |        |
| Integer | type    | √        |        |
| File    | cover   | √        | Image  |

### JSON Response
#### Success
```
Status Code : 200
{
  "status": "success",
  "comic": {
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

| Method | URI                     | Remark   |
|:------:| ----------------------- | -------- |
| POST   | /api/publish/{comic_id} | JWT Auth |

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
    "publish_by": {
      "id": *id*,
      "name": *name*
    },
    "updated_at": *updateTime*,
    "created_at": *createTime*,
    "id": *id*,
    "token": *token*
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

## 6. <a name="TagComic">Tag Comic</a>

| Method | URI                        | Remark |
|:------:| -------------------------- | ------ |
| POST   | /api/tag/{name}/{comic_id} |        |

### JSON Response
#### Success
```
Status Code: 200
{
  "status": "success",
  "tags": *tags[Array]*
}
```

#### Error
```
Status Code: 403
{
  "status": "error",
  "message": "Tag Exist"
}
```

## 7. <a name="UntagComic">Untag Comic</a>

| Method | URI                        | Remark |
|:------:| -------------------------- | ------ |
| Delete | /api/tag/{name}/{comic_id} |        |

### JSON Response
#### Success
```
Status Code: 200
{
  "status": "success",
  "tags": *tags[Array]*
}
```

#### Error
```
Status Code: 404
{
  "status": "error",
  "message": "Tag Not Found"
}
```

## 8. <a name="ListComics">List Comics</a>

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

## 9. <a name="ListTypes">List Types</a>

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

## 10. <a name="ViewUserInfo">View User Info</a>

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

## 11. <a name="ViewComicInfo">View Comic Info</a>

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

## 12. <a name="ViewComicCover">View Comic Cover</a>

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

## 13. <a name="ViewChapterPage">View Chapter Page</a>

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

## 14. <a name="BatchUploadChapterPages">Batch Upload Chapter Pages</a>

| Method | URI                               | Remark   |
|:------:| --------------------------------- | -------- |
| POST   | /api/publish/chapter/{chapter_id} | JWT Auth |

```
> Sort First
> index[].length must equal chapter.pages
>> it will check before upload images[]
> new_index[n] = 0 -> delete page[n]
```

```
if (index[] is not empty)
  images[] is required
else if (images[] is not empty)
  index[] is required
else
  new_index[] is required
```

### Input Parameter

| Type    | Name        | Required | Remark          |
| ------- | ----------- |:--------:| --------------- |
| Integer | index[]     |          | min: 1          |
| File    | images[]    |          | Image           |
| Integer | new_index[] |          | integer, min: 0 |

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
    "publish_by": {
      "id": *id*,
      "name": *name*
    },
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
Status Code: 400
{
  "status": "error",
  "message": "Length of new_index[] Not Match Pages"
}
- or -
Status Code: 400
{
  "status": "error",
  "message": "new_index[] Duplicate"
}
- or -
Status Code: 404
{
  "status": "error",
  "message": "Chapter Not Found"
}
```

## 15. <a name="SearchComics">Search Comics</a>

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

## 16. <a name="UpdateUserAvatar">Update User Avatar</a>

| Method | URI              | Remark   |
|:------:| ---------------- | -------- |
| POST   | /api/user/avatar | JWT Auth |

### Input Parameter

| Type | Name     | Required | Remark         |
| ---- | -------- |:--------:| -------------- |
| File | image    | √        | Image          |

### JSON Response
#### Success
```
Status Code: 200
{
  "status": "success",
  "user": {
    "id": *id*,
    "name": *name*,
    "avatar": *avatar*
  }
}
```

## 17. <a name="ViewUserAvatar">View User Avatar</a>

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

## 18. <a name="AddUserFavoriteComic">Add User Favorite Comic</a>

| Method | URI                      | Remark |
|:------:| ------------------------ | ------ |
| POST   | /api/favorite/{comic_id} |        |

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
Status Code: 403
{
  "status": "error",
  "message": "Favorite Exist"
}
```

## 19. <a name="DeleteUserFavoriteComic">Delete User Favorite Comic</a>

| Method | URI                      | Remark |
|:------:| ------------------------ | ------ |
| Delete | /api/favorite/{comic_id} |        |

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
  "message": "Favorite Not Found"
}
```

## 20. <a name="ViewUserFavoriteComics">View User Favorite Comics</a>

| Method | URI                      | Remark |
|:------:| ------------------------ | ------ |
| GET    | /api/user/{id}/favorite  |        |

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
