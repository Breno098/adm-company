function page (path) {
  return () => import(`~/pages/${path}`).then(m => m.default || m)
}

export default [
  { path: '/client', name: 'client.index', component: page('app/client/list.vue') },
  { path: '/client/:id?', name: 'client.form', component: page('app/client/form.vue') },

  { path: '/category', name: 'category.index', component: page('app/category/list.vue') },
  { path: '/category/:id?', name: 'category.form', component: page('app/category/form.vue') },

  { path: '/item', name: 'item.index', component: page('app/item/list.vue') },
  { path: '/item/:id?', name: 'item.form', component: page('app/item/form.vue') },

  { path: '/', name: 'welcome', component: page('welcome.vue') },

  { path: '/login', name: 'login', component: page('auth/login.vue') },
  { path: '/register', name: 'register', component: page('auth/register.vue') },
  { path: '/password/reset', name: 'password.request', component: page('auth/password/email.vue') },
  { path: '/password/reset/:token', name: 'password.reset', component: page('auth/password/reset.vue') },
  { path: '/email/verify/:id', name: 'verification.verify', component: page('auth/verification/verify.vue') },
  { path: '/email/resend', name: 'verification.resend', component: page('auth/verification/resend.vue') },

  { path: '/home', name: 'home', component: page('home.vue') },
  {
    path: '/settings',
    component: page('settings/index.vue'),
    children: [
      { path: '', redirect: { name: 'settings.profile' } },
      { path: 'profile', name: 'settings.profile', component: page('settings/profile.vue') },
      { path: 'password', name: 'settings.password', component: page('settings/password.vue') }
    ]
  },

  { path: '*', component: page('errors/404.vue') }
]
