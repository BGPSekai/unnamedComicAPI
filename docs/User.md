# User API Reference Guide
> Require JWT Auth

1. [View User Info](#ViewUserInfo)
2. [Reset Password](#ResetPassword)
3. [Update User Avatar](#UpdateUserAvatar)
4. [Publish Comic](#PublishComic)
5. [Publish Chapter](#PublishChapter)
6. [Batch Upload Chapter Pages](#BatchUploadChapterPages)
7. [Search Tags](#SearchTags)
8. [Tag Comic](#TagComic)
9. [Untag Comic](#UntagComic)
10. [Add User Favorite Comic](#AddUserFavoriteComic)
11. [Remove User Favorite Comic](#RemoveUserFavoriteComic)
12. [Comment Comic or Chapter](#CommentComicOrChapter)


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

## 4. <a name="PublishComic">Publish Comic</a>

| Method | URI          | Remark |
|:------:| ------------ | ------ |
| POST   | /api/publish |        |

### Input Parameter

| Type   | Name    | Required | Remark |
| ------ | ------- |:--------:| ------ |
| String | name    | √        |        |
| String | summary | √        |        |
| String | author  | √        |        |
| String | type[]  | √        |        |
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
    "author": *author*,
    "type": *type[Array]*,
    "published_by": {
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

| Method | URI                     | Remark |
|:------:| ----------------------- | ------ |
| POST   | /api/publish/{comic_id} |        |

### Input Parameter

| Type   | Name     | Required | Remark |
| ------ | -------- |:--------:| ------ |
| String | name     | √        |        |

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
    "published_by": {
      "id": *id*,
      "name": *name*
    },
    "updated_at": *updateTime*,
    "created_at": *createTime*,
    "id": *id*,
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

## 6. <a name="BatchUploadChapterPages">Batch Upload Chapter Pages</a>

| Method | URI                               | Remark |
|:------:| --------------------------------- | ------ |
| POST   | /api/publish/chapter/{chapter_id} |        |


### Input Parameter

| Type    | Name        | Required | Remark          |
| ------- | ----------- |:--------:| --------------- |
| Integer | index[]     |          | min: 1          |
| File    | images[]    |          | Image           |
| Integer | new_index[] |          | min: 0          |

#### Requirement Relational Table

|           | index   | images | new_index |
|:---------:|:-------:|:------:|:---------:|
| index     | ------- | √      |           |
| images    | √       | ------ |           |
| new_index |         |        | --------- |

```
> Sort First
> new_index[].length must equal chapter.pages
>> it will be checked before upload images[]
> new_index[n] = 0 -> delete page[n]
```

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
    "published_by": {
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

## 7. <a name="SearchTags">Search Tags</a>

| Method | URI             | Remark |
|:------:| --------------- | ------ |
| POST   | /api/tag/{name} |        |

### JSON Response
#### Success
```
Status Code: 200
{
  "status": "success",
  "tags": *tags[Array]*
}
```

## 8. <a name="TagComic">Tag Comic</a>

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

## 9. <a name="UntagComic">Untag Comic</a>

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

## 10. <a name="AddUserFavoriteComic">Add User Favorite Comic</a>

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

## 11. <a name="RemoveUserFavoriteComic">Remove User Favorite Comic</a>

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

## 12. <a name="CommentComicOrChapter">Comment Comic or Chapter</a>

| Method | URI          | Remark |
|:------:| ------------ | ------ |
| POST   | /api/comment |        |

### Input Parameter

#### Publish Comment

| Type    | Name       | Required | Remark |
| ------- | ---------- |:--------:| ------ |
| Integer | comic_id   | √        |        |
| String  | comment    | √        |        |

---

| Type    | Name       | Required | Remark |
| ------- | ---------- |:--------:| ------ |
| Integer | chapter_id | √        |        |
| String  | comment    | √        |        |

#### Update Comment

| Type    | Name    | Required | Remark |
| ------- | ------- |:--------:| ------ |
| Integer | id      | √        |        |
| String  | comment | √        |        |

### JSON Response
#### Success
```
Status Code: 200
{
  "status": "success",
  "comment": {
    "id": *id*,
    "comic_id": *comic_id*,
    "chapter_id": *chapter_id*,
    "comment": *comment*,
    "commented_by": {
      "id": *id*,
      "name": *name*
    },
    "updated_at": *updateTime*,
    "created_at": *createTime*
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
Status Code: 403
{
  "status": "error",
  "message": "Access is Denied"
}
```
