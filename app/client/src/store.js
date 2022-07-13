import { writable, derived, readable } from 'svelte/store';

export let currentUser = writable({
    idUser: 0,
    name: '',
    surname: '',
    email: '',
    password: '',
    projects: [],
    posts: [],
    bugs: {
        submitted: [],
        fixed: []
    }
});

export let currentUserId = derived(currentUser, $currentUser => $currentUser.idUser );

export let viewedUser = writable({
    idUser: 0,
    name: '',
    surname: '',
    email: '',
    password: '',
    projects: [],
    posts: [],
    bugs: {
        submitted: [],
        fixed: []
    }
});

export let viewedProject = writable({
    idProject: 0,
    name: '',
    description: '',
    members: [],
    posts: [],
    bugs: {
        submitted: [],
        fixed: []
    }
});
export let userIsProjectLeader = writable(false);
export let userIsProjectMember = writable(false);

export let allUserPosts = writable([]);
export let allUserBugs = writable([]);

function createModalControl() {
    const { subscribe, update } = writable({
        visible: false,
        type: '',
        content: ''
    });

    return {
        subscribe,
        show: function (showType, showContent) {
            update(value => {
                value.type = showType;
                value.content = showContent;
                value.visible = true;
                return value;
            });           
        },
        hide: function () {
            update(value => {
                value.type = '';
                value.content = '';
                value.visible = false;
                return value;
            });   
        }
    }
}

export const modalControl = createModalControl();