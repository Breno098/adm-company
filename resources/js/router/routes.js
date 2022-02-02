function page (path) {
  return () => import(`~/pages/${path}`).then(m => m.default || m)
}

export default [
  { path: '/order', name: 'order.index', component: page('app/order/index.vue') },
  { path: '/order/create', name: 'order.create', component: page('app/order/form.vue') },
  { path: '/order/show/:id', name: 'order.show', component: page('app/order/form.vue') },

  { path: '/appointment', name: 'appointment.index', component: page('app/appointment/list.vue') },
  { path: '/appointment/create', name: 'appointment.create', component: page('app/appointment/form.vue') },
  { path: '/appointment/show/:id', name: 'appointment.show', component: page('app/appointment/form.vue') },

  { path: '/client', name: 'client.index', component: page('app/client/list.vue') },
  { path: '/client/create', name: 'client.create', component: page('app/client/form.vue') },
  { path: '/client/show/:id', name: 'client.show', component: page('app/client/form.vue') },

  { path: '/employee', name: 'employee.index', component: page('app/employee/list.vue') },
  { path: '/employee/create', name: 'employee.create', component: page('app/employee/form.vue') },
  { path: '/employee/show/:id', name: 'employee.show', component: page('app/employee/form.vue') },

  { path: '/category', name: 'category.index', component: page('app/category/list.vue') },
  { path: '/category/create', name: 'category.create', component: page('app/category/form.vue') },
  { path: '/category/show/:id', name: 'category.show', component: page('app/category/form.vue') },

  { path: '/product', name: 'product.index', component: page('app/product/list.vue')},
  { path: '/product/create', name: 'product.create', component: page('app/product/form.vue') },
  { path: '/product/show/:id', name: 'product.show', component: page('app/product/form.vue') },

  { path: '/service', name: 'service.index', component: page('app/service/list.vue')},
  { path: '/service/create', name: 'service.create', component: page('app/service/form.vue') },
  { path: '/service/show/:id', name: 'service.show', component: page('app/service/form.vue') },

  { path: '/expense', name: 'expense.index', component: page('app/expense/list.vue')},
  { path: '/expense/create', name: 'expense.create', component: page('app/expense/form.vue') },
  { path: '/expense/show/:id', name: 'expense.show', component: page('app/expense/form.vue') },

  { path: '/settings-user-profile', name: 'settings-user-profile', component: page('app/profile/index.vue') },
  { path: '/settings-user-password', name: 'settings-user-password', component: page('app/profile/password.vue') },
  { path: '/settings-first-access', name: 'settings-first-access', component: page('app/profile/first_access.vue') },

  { path: '/user', name: 'user.index', component: page('app/user/list.vue') },
  { path: '/user/create', name: 'user.create', component: page('app/user/form.vue') },
  { path: '/user/show/:id', name: 'user.show', component: page('app/user/form.vue') },

  { path: '/home', name: 'home', component: page('app/home/index.vue') },
  { path: '/', name: 'home.default', component: page('app/home/index.vue') },

  { path: '/file', name: 'file.index', component: page('app/file/index.vue') },

  { path: '/file/employee-receipt', name: 'file.employee-receipt.index', component: page('app/file/employee-receipt/list.vue') },
  { path: '/file/employee-receipt/create', name: 'file.employee-receipt.create', component: page('app/file/employee-receipt/form.vue') },
  { path: '/file/employee-receipt/show/:id', name: 'file.employee-receipt.show', component: page('app/file/employee-receipt/form.vue') },

  { path: '/report', name: 'report.index', component: page('app/report/index.vue') },

  { path: '/report/finance', name: 'report.finance.index', component: page('app/report/finance/index.vue') },
  { path: '/report/finance/details/:month/:year', name: 'report.finance.details', component: page('app/report/finance/details.vue') },

  { path: '/login', name: 'login', component: page('auth/login.vue') },
  { path: '/register', name: 'register', component: page('auth/register.vue') },

  { path: '/password/reset', name: 'password.request', component: page('auth/password/email.vue') },
  { path: '/password/reset/:token', name: 'password.reset', component: page('auth/password/reset.vue') },
  { path: '/email/verify/:id', name: 'verification.verify', component: page('auth/verification/verify.vue') },
  { path: '/email/resend', name: 'verification.resend', component: page('auth/verification/resend.vue') },

  { path: '*', component: page('errors/404.vue') }
]
