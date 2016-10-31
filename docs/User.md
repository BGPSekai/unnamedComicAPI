# User API Reference Guide
> Require JWT Auth

1. [View User Info](#ViewUserInfo)
2. [Reset Password](#ResetPassword)
3. [Update User Avatar](#UpdateUserAvatar)
4. [Publish Comic](#PublishComic)
5. [Publish Chapter](#PublishChapter)
6. [Batch Upload Chapter Pages](#BatchUploadChapterPages)
7. [Tag Comic](#TagComic)
8. [Untag Comic](#UntagComic)
9. [Add User Favorite Comic](#AddUserFavoriteComic)
10. [Remove User Favorite Comic](#RemoveUserFavoriteComic)


## 1. <a name="ViewUserInfo">View User Info</a>

| Method | URI       | Remark |
|:------:| --------- | ------ |
| GET    | /api/user |        |

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

## 2. <a name="ResetPassword">Reset Password</a>

| Method | URI             | Remark |
|:------:| --------------- | ------ |
| POST   | /api/auth/reset |        |

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
  "message": "Password Reset"
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

## 3. <a name="UpdateUserAvatar">Update User Avatar</a>

| Method | URI              | Remark |
|:------:| ---------------- | ------ |
| POST   | /api/user/avatar |        |

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

## 8. <a name="PublishComic">Publish Comic</a>

| Method | URI          | Remark |
|:------:| ------------ | ------ |
| POST   | /api/publish |        |

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

## 4. <a name="PublishChapter">Publish Chapter</a>

| Method | URI                     | Remark |
|:------:| ----------------------- | ------ |
| POST   | /api/publish/{comic_id} |        |

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

## 5. <a name="BatchUploadChapterPages">Batch Upload Chapter Pages</a>

| Method | URI                               | Remark |
|:------:| --------------------------------- | ------ |
| POST   | /api/publish/chapter/{chapter_id} |        |

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
| Integer | new_index[] |          | min: 0 |

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

## 9. <a name="AddUserFavoriteComic">Add User Favorite Comic</a>

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

## 10. <a name="RemoveUserFavoriteComic">Remove User Favorite Comic</a>

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
