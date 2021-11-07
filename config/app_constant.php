<?php
/*/////////////// Pagination ////////////////////////*/
const PAGINATE_LIMIT = '5';

/*/////////////// DOMAIN ////////////////////////*/
const DOMAIN = 'https://test.com/';

/*/////////////// Message ////////////////////////*/

const ERROR_RETRY = 'Vui lòng thử lại';
const ERROR_FULL_INFOR = 'Vui lòng điền đầy đủ các trường và thử lại.';
const ERROR_ROLE_ADMIN = 'Bạn không có quyền truy cập vào trang Admin.';

//Search
const ERROR_SEARCH_NOT_FOUND = 'Không tìm thấy kết quả tìm kiếm.';

//Cart
const ERROR_CART_EMPTY = 'Giỏ hàng trống nên không thể đặt hàng.';
const ERROR_REGISTER_FAILED_RE_ORDER = 'Hệ thống đăng ký tài khoản thất bại. Vui lòng đặt hàng lại.';

//Account
const ERROR_LOCK_ACCOUNT = 'Tài khoản của bạn đã bị khóa.';
const ERROR_ACCOUNT_NOT_CHANGED = 'Tài khoản không có sự thay đổi.';
const ERROR_UPDATED_ACCOUNT = 'Tài khoản chưa được cập nhật. Vui lòng thử lại.';

const SUCCESS_ACCOUNT = 'Đăng ký tài khoản thành công.';
const SUCCESS_UPDATED_ACCOUNT = 'Tài khoản đã được cập nhật thành công.';

//Email và Pws
const ERROR_EMAIL_EMPTY = 'Email không tồn tại.';
const EMAIL_ALREADY_EXISTS = 'Địa chỉ Email đã tồn tại';
const ERROR_NOT_INPUT_EMAIL = 'Chưa nhập Email, vui lòng kiểm tra lại!';
const ERROR_EMAIL_PWS_INCORRECT = 'Email hoặc mật khẩu chưa chính xác.';
const ERROR_PWS_INCORRECT = 'Mật khẩu sai. Vui lòng kiểm tra lại.';

const ERROR_PASSWORD_NOT_MATCH = 'Mật khẩu không khớp. Vui lòng kiểm tra lại.';
const ERROR_PASSWORD_NOT_CHANGED = 'Mật khẩu không có sự thay đổi!!!';

const SUCCESS_PASSWORD_CHANGED = 'Mật khẩu của bạn đã được thay đổi!!!';

//Category
const ERROR_CATEGORY_EMPTY = 'Danh mục không tồn tại.';
const ERROR_CATEGORY_NOT_CHANGED = 'Danh mục không có sự thay đổi.';
const ERROR_UPDATED_CATEGORY = 'Danh mục chưa được cập nhật. Vui lòng thử lại!!!';
const ERROR_DEL_CATEGORY = 'Danh mục chưa được xóa. Vui lòng thử lại';
const ERROR_CATEGORY_HAS_PRODUCT_NOT_DEL = 'Danh mục còn sản phẩm. Không thể xóa.';

const SUCCESS_ADD_CATEGORY = 'Danh mục đã được thêm thành công.';
const SUCCESS_DEL_CATEGORY = 'Danh mục đã được xóa thành công.';
const SUCCESS_UPDATED_CATEGORY = 'Danh mục đã được cập nhật thành công.';

//Product
const ERROR_PRODUCT_EMPTY = 'Sản phẩm không tồn tại.';
const ERROR_PRODUCT_NOT_CHANGED = 'Sản phẩm không có sự thay đổi!!!';
const ERROR_PRODUCT_DATA_CHANGED_NOT_CONFIRM = 'Dữ liệu đã bị thay đổi. Không thể xác nhận chỉnh sửa sản phẩm!!!';
const ERROR_DEL_PRODUCT = 'Sản phẩm chưa được xóa. Vui lòng thử lại!!!';

const SUCCESS_ADD_PRODUCT = 'Sản phẩm đã được thêm thành công.';
const SUCCESS_UPDATED_PRODUCT = 'Sản phẩm đã được cập nhật thành công.';
const SUCCESS_DEL_PRODUCT = 'Sản phẩm đã được xóa thành công.';

//Orders
const ERROR_ORDER_EMPTY = 'Đơn hàng không tồn tại.';
const ERROR_ORDER_NOT_CHANGED = 'Đơn hàng không có sự thay đổi!!!';
const ERROR_ORDER_DATA_CHANGED_NOT_CONFIRM = 'Dữ liệu đã bị thay đổi. Không thể xác nhận đơn hàng!!!';

const SUCCESS_UPDATED_ORDER = 'Đơn hàng đã được cập nhật thành công.';

//Users
const ERROR_USER_EMPTY = 'Người dùng không tồn tại.';
const ERROR_USER_NOT_CHANGED = 'Dữ liệu không có sự thay đổi!!!';
const ERROR_DATA_FILTER_CHANGED = 'Dữ liệu lọc đã bị thay đổi!!!';
const ERROR_USER_DATA_CHANGED_NOT_ADD_CONFIRM = 'Dữ liệu đã bị thay đổi. Không thể xác nhận thêm người dùng!!!';
const ERROR_USER_DATA_CHANGED_NOT_UPDATED_CONFIRM = 'Dữ liệu đã bị thay đổi. Không thể xác nhận cập nhật người dùng!!!';
const ERROR_USER_LOCK = 'User chưa được khóa. Vui lòng thử lại!!!';
const ERROR_USER_UNLOCK = 'User chưa được mở. Vui lòng thử lại!!!';

const SUCCESS_ADD_USER = 'User đã được thêm thành công.';
const SUCCESS_UPDATED_USER = 'User đã được cập nhật thành công.';
const SUCCESS_USER_LOCK = 'User đã được khóa thành công';
const SUCCESS_USER_UNLOCK = 'User đã được mở thành công';

/*/////////////// ACTION ////////////////////////*/

//Authexs
const AUTH_INDEX = 'index';
const AUTH_LOGIN = 'login';

//NormalUsers
const NORMALUSER_INDEX = 'index';
const NORMALUSER_INFORMATION_CART = 'informationCart';
const NORMALUSER_PAGE_ERROR = 'pageError';
const NORMALUSER_INPUT_USER = 'inputUser';
const NORMALUSER_COMPLETE_ORDER = 'completeOrder';
const NORMALUSER_MY_ACCOUNT = 'myAccount';
const NORMALUSER_HISTORY_ORDER = 'historyOrders';
const NORMALUSER_INFO_CUSTOMER = 'infoCustomer';

//Admin
const ADMIN_LIST_CATEGORIES = 'listCategories';
const ADMIN_LIST_ORDERS = 'listOrders';
const ADMIN_LIST_PRODUCTS = 'listProducts';
const ADMIN_LIST_USERS = 'listUsers';

/*/////////////// URL ////////////////////////*/

const URL_INDEX_ADMIN = '/admin';
const URL_LOGOUT = 'logout';
const URL_REGISTER = '/register';
const URL_FORGOT_PWS = '/forgotPassword';

const URL_NORMALUSER_ADD_ORDERS = '/addOrders';
const URL_NORMALUSER_ADD_ORDERS_NONE_LOGIN = '/addOrdersNoneLogin';
const URL_NORMALUSER_CONFIRM_ORDER = '/confirmOrder';
const URL_NORMALUSER_CONFIRM = '/confirm';
const URL_NORMALUSER_INPUT = '/input';

const URL_ADMIN_LIST_ORDERS = '/admin/list-orders';
const URL_ADMIN_LIST_PRODUCTS = '/admin/list-products';
const URL_ADMIN_LIST_USER = '/admin/list-user';
const URL_ADMIN_LIST_CATEGORIES = '/admin/list-categories';

/*/////////////// Name Router ////////////////////////*/
const NAME_LOGIN = 'login';

/*/////////////// Validate ////////////////////////*/
const ERROR_NULL_PHONE_NUMBER = 'Số điện thoại không được để trống.';
const ERROR_INVALID_PHONE_NUMBER = 'Số điện thoại không đúng định dạng.';
const ERROR_NULL_USERNAME = 'Tên không được để trống.';
const ERROR_NULL_ADDRESS = 'Địa chỉ không được để trống.';