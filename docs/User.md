# User API Reference Guide
> Require JWT Auth

1. [View User Info](#ViewUserInfo)
2. [Reset Password](#ResetPassword)
3. [Update User Info](#UpdateUserInfo)
4. [Publish Comic](#PublishComic)
5. [Update Comic](#UpdateComic)
6. [Publish Chapter](#PublishChapter)
7. [Batch Upload Chapter Pages](#BatchUploadChapterPages)
8. [Search Tags](#SearchTags)
9. [Tag Comic](#TagComic)
10. [Untag Comic](#UntagComic)
11. [Add User Favorite Comic](#AddUserFavoriteComic)
12. [Remove User Favorite Comic](#RemoveUserFavoriteComic)
13. [Comment Comic or Chapter](#CommentComicOrChapter)


```
timestamp: created_at, updated_at
```

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
    "from": *from*,
    "sex": *sex*,
    "birthday": *birthday*,
    "location": *location*,
    "sign": *sign*,
    "blocked_until": *unblockedTime*,
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

## 3. <a name="UpdateUserInfo">Update User Info</a>

| Method | URI              | Remark |
|:------:| ---------------- | ------ |
| PATCH  | /api/user/update |        |

### Input Parameter

| Type    | Name     | Required | Remark |
| ------- | -------- |:--------:| ------ |
| String  | name     |          |        |
| File    | avatar   |          | Image  |
| Boolean | sex      |          |        |
| String  | birthday |          | Date   |
| String  | location |          |        |
| String  | sign     |          |        |

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

| Type    | Name    | Required | Remark |
| ------- | ------- |:--------:| ------ |
| String  | name    | √        |        |
| String  | summary | √        |        |
| String  | author  | √        |        |
| Integer | types[] | √        |        |
| File    | cover   | √        | Image  |

### JSON Response
#### Success
```
Status Code : 200
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
    }
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

## 5. <a name="UpdateComic">Update Comic</a>

| Method | URI             | Remark |
|:------:| --------------- | ------ |
| PATCH  | /api/comic/{id} |        |

### Input Parameter

| Type    | Name    | Required | Remark |
| ------- | ------- |:--------:| ------ |
| String  | name    |          |        |
| String  | summary |          |        |
| String  | author  |          |        |
| Integer | types[] |          |        |
| File    | cover   |          | Image  |

### JSON Response
#### Success
```
Status Code : 200
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
    }
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

## 6. <a name="PublishChapter">Publish Chapter</a>

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
    "id": *id*,
    "comic_id": *comic_id*,
    "name": *name*,
    "pages": *pages*,
    "published_by": {
      "id": *id*,
      "name": *name*
    }
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

## 7. <a name="BatchUploadChapterPages">Batch Upload Chapter Pages</a>

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
```

## 8. <a name="SearchTags">Search Tags</a>

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

## 9. <a name="TagComic">Tag Comic</a>

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
Status Code: 400
{
  "status": "error",
  "message": *message[Array]*
}
- or -
Status Code: 403
{
  "status": "error",
  "message": "Tag Exist"
}
```

## 10. <a name="UntagComic">Untag Comic</a>

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

## 11. <a name="AddUserFavoriteComic">Add User Favorite Comic</a>

| Method | URI                      | Remark |
|:------:| ------------------------ | ------ |
| POST   | /api/favorite/{comic_id} |        |

### JSON Response
#### Success
```
Status Code: 200
{
  "status": "success",
  "message": "Add Favorite Success"
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
  "message": "Favorite Exist"
}
```

## 12. <a name="RemoveUserFavoriteComic">Remove User Favorite Comic</a>

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

## 13. <a name="CommentComicOrChapter">Comment Comic or Chapter</a>

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
    }
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
