import home from './components/Home';
import good from './components/Good';
import type from './components/Type';

export default [
    {path: '/', redirect: '/home'},
    {path: '/home', component: home},
    {path: '/good', component: good},
    {path: '/type', component: type},
];
