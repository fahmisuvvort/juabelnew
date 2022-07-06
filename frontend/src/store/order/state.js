export default function () {
  return {
    orders: {
      data: [],
      ready: false,
      available: true,
      isLoadMore: false,
      canLoadMore: false
    },
    customer_order: {
      data: [],
      ready: false,
      available: true,
      isLoadMore: false,
      canLoadMore: false
    },
    invoice: null,
    transaction: null,
    formOrder: {
      items: [],
      quantity: 0,
      weight: 0,
      subtotal: 0,
      shipping_courier_name:'',
      shipping_cost: 0,
      shipping_courier_service: '',
      coupon_discount: 0,
      customer_name:'',
      customer_email: '',
      customer_phone: '',
      payment_method: '',
      payment_name: '',
      payment_type: '',
      payment_code: '',
      payment_fee: 0,
      address: '',
      customer_service_id: ''
    },
    orderItems: [],
  }
}
