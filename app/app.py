from flask import *

app = Flask(__name__)

# universal
@app.route("/image/<imageFile>", methods=['GET'])
def getImage(imageFile):
    pass

# home
@app.route("/home/banner", methods=['GET'])
def getBanner():
    pass

@app.route("/home/category", methods=['GET'])
def getCategory():
    pass

# auth
@app.route("/sign-up", methods=['POST'])
def signUp():
    pass

@app.route("/sign-in", methods=['POST'])
def signIn():
    pass

# product list
@app.route("/products",methods=['GET'])
def getProductList():
    pass

@app.route("/categories",methods=['GET'])
def getCategory():
    pass

@app.route("/products/search_image",methods=['POST'])
def searchProductByImage():
    pass

# product detail page
@app.route("/products/<id>",methods=['GET'])
def getProductDetail(id):
    pass

@app.route("/cart",methods=['POST'])
def addToCart():
    pass

# cart
@app.route("/cart",methods=['GET'])
def getUsersCart():
    pass

@app.route("/user/shipping_address",methods=['GET'])
def getUserShippingAddress():
    pass

@app.route("/shipping_price",methods=['GET'])
def getShippingPrice():
    pass

@app.route("/order",methods=['POST'])
def createOrder():
    pass

@app.route("/cart/cart_id",methods=['DELETE'])
def deleteCartItem():
    pass

# profile page
@app.route("/user",methods=['GET'])
def userDetails():
    pass

@app.route("/user/shipping_address",methods=['POST'])
def changeShippingAddress():
    pass

@app.route("/user/balance",methods=['POST'])
def topUpBalance():
    pass

@app.route("/user/balance",methods=['GET'])
def getUserBalance():
    pass

@app.route("/order",methods=['GET'])
def userOrder():
    pass

# admin page
@app.route("/orders",methods=['GET'])
def getOrders():
    pass

@app.route("/products",methods=['POST'])
def createProduct():
    pass

@app.route("/products",methods=['PUT'])
def updateProduct():
    pass

@app.route("/products/product_id",methods=['DELETE'])
def deleteProduct():
    pass

@app.route("/categories",methods=['POST'])
def createCategory():
    pass

@app.route("/categories/category_id",methods=['PUT'])
def updateCategory():
    pass

@app.route("/categories/category_id",methods=['DELETE'])
def deleteCategory():
    pass

@app.route("/sales",methods=['GET'])
def getTotalSales():
    pass

if __name__ == '__main__':
    app.debug = True
    app.run(port=5000)