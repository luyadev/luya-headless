# LUYA Headless Documentation

Table of contents

+ [Endpoint](endpoint.md)
+ [Active Endpoint](active-endpoint.md)
+ [Pagination](pagination.md)
+ [CMS Menu & page](cms.md)
+ [Render CMS Page & Blocks](cms-renderer.md)

## General information

The LUYA headless client is mainly though to connect to LUYA Admin APIs but you can also connect to any other REST Api. In order to use the endpoint predefined definitions the REST API should follow this architecture:

|Method|Endpoint|Example|Description
|------|--------|-------|----
|GET|`/<ENDPOINT>`|/users|list all users page by page
|HEAD|`/<ENDPOINT>`|/users|show the overview information of user listing
|POST|`/<ENDPOINT>`|/users|create a new user
|GET|`/<ENDPOINT>/<ID>`|/users/123|return the details of the user 123
|HEAD|`/<ENDPOINT>/<ID>`|/users/123|show the overview information of user 123
|PATCH|`/<ENDPOINT>/<ID>`|/users/123 and PUT /users/123|update the user 123
|DELETE|`/<ENDPOINT>/<ID>`|/users/123|delete the user 123
|OPTIONS|`/<ENDPOINT>`|/users|show the supported verbs regarding endpoint /users
|OPTIONS|`/<ENDPOINT>/<ID>`|/users/123|show the supported verbs regarding endpoint /users/123