<script>
    import { onMount } from "svelte";
    import { router  } from 'tinro';
    import { currentUser, modalControl } from './store';
    import parseCookies from './services/parseCookies.js';
    import Routes from './Routes.svelte';
    import Modal from './lib/misc/Modal.svelte';
    
    const cookies = parseCookies();

    const getCurrentUser = async () => {
        if(cookies.PHPSESSID != undefined) {
            try {
                const res = await fetch(`/api/users?sessid=${cookies.PHPSESSID}`);
                const json = await res.json();

                if(res.ok) {
                    if(json.length === 0) {
                        throw new Error(final);
                    }
                    const user = await json[0];

                    const projectsRes = await fetch(`/api/users/projects?id=${user.idUser}`);
                    const projects = await projectsRes.json();
                    const postsRes = await fetch(`/api/users/posts?id=${user.idUser}`);
                    const posts = await postsRes.json();
                    const submittedBugsRes = await fetch(`/api/users/bugs/submitted?id=${user.idUser}`);
                    const submittedBugs = await submittedBugsRes.json();
                    const fixedBugsRes = await fetch(`/api/users/bugs/fixed?id=${user.idUser}`);
                    const fixedBugs = await fixedBugsRes.json();

                    user.projects = projects;
                    user.posts = posts;
                    user.bugs = await {
                        submitted: submittedBugs,
                        fixed: fixedBugs
                    }
                    
                    currentUser.set(user);
                    return user;
                } else {
                    document.cookie = `PHPSESSID=0;expires=Thu, 01 Jan 1970 00:00:00 GMT`;
                    throw new Error();
                }
            } catch(e) {
                document.cookie = `PHPSESSID=0;expires=Thu, 01 Jan 1970 00:00:00 GMT`;
                throw new Error(e);
            }
        }
    }

    onMount(async () => {
        if(cookies.PHPSESSID) {
            console.log('user is logged in');
            // const user = await getCurrentUser();
        } else {
            console.log('user is not logged in');
            router.goto('/login');
        }
    });
</script>

<svelte:head>
    <title>Bug Tracker</title>
    <link rel="icon" href="./lib/icons/bug_icon.svg" type="image/x-icon">
</svelte:head>

<Modal />
{#if $currentUser.idUser}
<Routes />
{:else}
    {#await getCurrentUser()}
    Loading...
    {:then currentUser} 
        <Routes />
    {/await}
{/if}

<style>
    
</style>