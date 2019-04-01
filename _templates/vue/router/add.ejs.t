---
to: resources/js/router/<%= name %>s.js
---
import <%= Name %>Index from '@/pages/<%= Name %>/Index'
import <%= Name %>Show from '@/pages/<%= Name %>/Show'
import <%= Name %>Form from '@/pages/<%= Name %>/Form'

export default [
  {
    path: '/<%= name %>s',
    name: '<%= Name %>Index',
    component: <%= Name %>Index
  },
  {
    path: '/<%= name %>s/:id',
    name: '<%= Name %>Show',
    component: <%= Name %>Show
  },
  {
    path: '/<%= name %>s/:id/edit',
    name: '<%= Name %>Edit',
    component: <%= Name %>Form
  },
  {
    path: '/<%= name %>s/create',
    name: '<%= Name %>Create',
    component: <%= Name %>Form
  }
]
