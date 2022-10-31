# Final Project Backend Startup Campus Kelompok Joko Kendil

This project is built with [Python](https://www.python.org/) and [Flask](https://flask.palletsprojects.com/en/2.2.x/).

## Repository Structure

The repository is structured as follows:

- `main` is main branch of the repository and contains the latest stable version of the code.
- `dev` is the development branch of the repository and contains the latest development version of the code.
- `feature/feature_name` branches are used to develop new features and are merged into `dev` when they are ready.
- commits formatted as `feat/feature_name` are used to fix bugs in the code and are merged into `dev` when they are ready.

## Installation

### Prerequisites

- Docker
- Python 3.9
- Postman

# Core API

## Endpoint Progress

### Universal

-   [ ] Get Image `/image/{image_name.ext}`

### Home

-   [ ] Get Banner `/home/banner`
-   [ ] Get Category `/home/category`

### Auth

-   [ ] Sign Up `/sign-up`
-   [ ] Sign In `/sign-in`

### Product List

-   [ ] Get Product List `/products`
-   [ ] Get Category `/categories`
-   [ ] Search Product bt Image `/products/search_image`

### Product Details

-   [ ] Get Product List `/products/{id}`
-   [ ] Add to Cart `/cart`

### Cart

-   [ ] Get User Cart `/cart`
-   [ ] Get User Shipping Address `/user/shipping_address`
-   [ ] Get Shipping Price `/shipping_price`
-   [ ] Create Order `/order`
-   [ ] Delete Cart Item `/cart/cart_id`

### User Profile

-   [ ] Get User Details `/user`
-   [ ] Change Shipping Address `/user/shipping_address`
-   [ ] Top-up Balance `/user/balance`
-   [ ] Get User Balance `/user/balance`
-   [ ] Get User Shipping Address `/user/shipping_address`
-   [ ] User Orders `/order`

### Admin Dash [Admin-only]

-   [ ] Get Orders `/admin/orders`
-   [ ] Create Product `/products`
-   [ ] Create Category `/categories`
-   [ ] Get Total Sales `/sales`

## Universal

| Method | URI                       | Note                     |
| ------ | ------------------------- | ------------------------ |
| `GET`  | `/image/{image_name.ext}` | `Auth-neutral` Get Image |

## Home

| Method | URI              | Note                        |
| ------ | ---------------- | --------------------------- |
| `GET`  | `/home/banner`   | `Auth-neutral` Get Banner   |
| `GET`  | `/home/category` | `Auth-neutral` Get Category |

## Auth

| Method | URI        | Note               |
| ------ | ---------- | ------------------ |
| `POST` | `/sign-up` | `Non-Auth` Sign Up |
| `POST` | `/sign-in` | `Non-Auth` Sign In |

## Product List

| Method | URI                      | Note                                   |
| ------ | ------------------------ | -------------------------------------- |
| `GET`  | `/products`              | `Auth-neutral` Get Product List        |
| `GET`  | `/categories`            | `Auth-neutral` Get Category            |
| `POST` | `/products/search_image` | `Auth-neutral` Search Product by Image |

## Product Details

| Method | URI              | Note                               |
| ------ | ---------------- | ---------------------------------- |
| `GET`  | `/products/{id}` | `Auth-neutral` Get Product Details |
| `POST` | `/cart`          | `Auth` Add to Cart                 |

## Cart

| Method   | URI                      | Note                               |
| -------- | ------------------------ | ---------------------------------- |
| `GET`    | `/cart`                  | `Auth` Get User Cart               |
| `GET`    | `/user/shipping_address` | `Auth` Get User Shipping Address   |
| `GET`    | `/shipping_price`        | `Auth+haveCart` Get Shipping Price |
| `POST`   | `/order`                 | `Auth` Create Order                |
| `DELETE` | `/cart/cart_id`          | `Auth` Delete Cart Item            |

## User Profile

| Method | URI                      | Note                             |
| ------ | ------------------------ | -------------------------------- |
| `GET`  | `/user`                  | `Auth` Get User Details          |
| `POST` | `/user/shipping_address` | `Auth` Change Shipping Address   |
| `PUT`  | `/user/balance`          | `Auth` Top-up Balance            |
| `GET`  | `/user/balance`          | `Auth` Get User Balance          |
| `GET`  | `/user/shipping_address` | `Auth` Get User Shipping Address |
| `GET`  | `/order`                 | `Auth` User Orders               |

## Admin Dash [Admin-only]

| Method | URI             | Note                         |
| ------ | --------------- | ---------------------------- |
| `GET`  | `/admin/orders` | `Auth+Admin` Get Orders      |
| `POST` | `/products`     | `Auth+Admin` Create Product  |
| `POST` | `/categories`   | `Auth+Admin` Create Category |
| `GET`  | `/sales`        | `Auth+Admin` Get Total Sales |
