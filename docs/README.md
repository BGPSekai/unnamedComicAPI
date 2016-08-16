# Comic API Reference Guide

1. [Register](#Register)
2. [Auth Login](#AuthLogin)
3. [Publish Comic](#PublishComic)
4. [Publish Chapter](#PublishChapter)
5. [List Comics](#ListComics)
6. [View User Info](#ViewUserInfo)
7. [View Comic Info](#ViewComicInfo)
8. [View Comic Cover](#ViewComicCover)
9. [View Chapter](#ViewChapter)

## 1. <a name="Register">Register</a>

Method | URI
--- | ---
POST | /api/auth/register

### Input Parameter

Type | Name | Required | Remark
--- | --- | --- | ---
String | name | ✔ | 
Email | email | ✔ | 
String | password | ✔ | 
String | password_confirmation | ✔ | 

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

Method | URI
--- | ---
POST | /api/auth

### Input Parameter

Type | Name | Required | Remark
--- | --- | --- | ---
Email | email | ✔ | 
String | password | ✔ | 

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

## 3. <a name="PublishComic">Publish Comic</a>

Method | URI
--- | ---
POST | /api/publish

### Input Parameter

Type | Name | Required | Remark
--- | --- | --- | ---
String | name | ✔ | 
String | summary | ✔ | 
Image | cover | ✔ | 

### JSON Response
#### Success
```
Status Code : 200
{
  "status": "success",
  "comic": {
  "name": *name*,
  "summary": *summary*,
  "publish_by": *publish_by*,
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

## 4. <a name="Publish Chapter">Publish Chapter</a>

Method | URI
--- | ---
POST | /api/publish/{id}

### Input Parameter

Type | Name | Required | Remark
--- | --- | --- | ---
String | name | ✔ | 
Image | image[] | ✔ | 

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
    "publish_by": *publish_by*,
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

## 5. <a name="ListComics">List Comics</a>

Method | URI
--- | ---
GET | /api/comic/page/{page}

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
      "chapters": *chaoters*,
      "created_at": *createTime*,
      "updated_at": *updateTime*
    },
    ...
  ]
}
```

## 6. <a name="ViewUserInfo">View User Info</a>

Method | URI
--- | ---
GET | /api/user/{id}

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

## 7. <a name="ViewComicInfo">View Comic Info</a>

Method | URI
--- | ---
GET | /api/comic/{id}

### JSON Response
#### Success
```
{
  "status": "success",
  "comic": {
    "id": *id*,
    "name": *name*,
    "summary": *summary*,
    "publish_by": *publish_by*,
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

Method | URI
--- | ---
GET | /api/comic/{id}/cover

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

## 8. <a name="ViewComicChapter">View Comic Chapter</a>

Method | URI
--- | ---
GET | /api/comic/chapter/{page}

### Input Parameter

Type | Name | Required | Remark
--- | --- | --- | ---
String | token | ✔ | JWT token from View Comic Info

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
