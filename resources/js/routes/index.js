import Router from "vue-router";
import EventsList from '../components/pages/EventsList';
import EventEdit from '../components/pages/EventEdit';
import EventCreate from '../components/pages/EventCreate';
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
    }
];


const router = new Router({
    mode: 'history',
    routes
  })


export default router;
