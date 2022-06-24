
# Island

## API Endpoints

### Users

- **[POST]** `wp-json/island/v1/users/generate_users` - To generate users
- **[GET]** `wp-json/island/v1/users/list` - Users list
- **[POST]** `wp-json/island/v1/users/generate_items?user_id=1` - To generate items for this user
- **[GET]** `wp-json/island/v1/users/items?user_id=1` - User items list
- **[POST]** `wp-json/island/v1/users/rename?user_id=1&user_name=NewName` - Rename user

### Trade center

- **[POST]** `wp-json/island/v1/trade_center/sell?item_slug=water&=user_id=1` - Sell item
- **[POST]** `wp-json/island/v1/trade_center/buy/?buyer_id=3&seller_id=2&buy_item_slug=dog&sell_item_slugs=water,pants` - Buy item
- **[GET]** `wp-json/island/v1/trade_center/items` - Items list
