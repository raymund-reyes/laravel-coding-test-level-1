import Router from "vue-router";
import EventsList from '../components/pages/EventsList';
import EventEdit from '../components/pages/EventEdit';
import EventCreate from '../components/pages/EventCreate';
import Login from '../components/pages/Login';
import ExternalAPI from '../components/pages/ExternalAPI';
export const routes = [
    {
        name: 'home',
        path: '/events',
        component: EventsList
    },

    {
        name: 'create-event',
        path: '/events/create',
        component: EventCreate
    },

    {
        name: 'edit-event',
        path: '/events/:id/:edit?',
        component: EventEdit
    },

    {
        name: 'login',
        path: '/',
        component: Login
    },

    {
        name: 'external-api',
        path: '/external-api',
        component: ExternalAPI
    }
];


const router = new Router({
    mode: 'history',
    routes
  })


export default router;
