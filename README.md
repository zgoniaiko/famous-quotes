# famous-quotes

Application provide API for CRUD "famous quotes".

API looks like:

 * `GET` `/authors/{id}` - Retrieve author by specified id
 * `GET` `/quotes` - List of all quotes
 * `GET` `/quotes/random` - Retrieve 1 random quote
 * `GET` `/quotes/{id}` - Retrieve quote by specified id
 * `POST` `/quotes` - Create new quote
 * `PUT` `/quotes/{id}` - Update quote by specified id
 * `DELETE` `/quotes/{id}` - Delete quote by specified id

Auth handled with basic auth
