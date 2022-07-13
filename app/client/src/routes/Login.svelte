<script>
    import { onMount } from 'svelte';
    import { router } from 'tinro';
    import { fade, fly } from 'svelte/transition';
    import { currentUser, modalControl } from '../store';
    import TextField from '../lib/misc/TextField.svelte';
    import Submit from '../lib/login/Submit.svelte';
    import EmailField from '../lib/misc/EmailField.svelte';
    import PasswordField from '../lib/misc/PasswordField.svelte';
    import parseCookies from '../services/parseCookies.js';

    let email = '';
    let password = '';
    let name = '';
    let surname = '';

    let mode = 'login';
    let flyLength = 300;

    let loaded = false;
    onMount(() => {
        loaded = true;
    });

    const getCurrentUser = async () => {
        const cookies = parseCookies();
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
                    throw new Error(user);
                }
            } catch(e) {
                throw new Error(e);
            }
        }
    }

    const handleLogin = async () => {
        const loginData = new FormData();
        loginData.append('email', email);
        loginData.append('password', password);
        try {
            const res = await fetch('/api/login', {
                method: 'POST',
                body: loginData
            });
            if(res.ok) {
                await getCurrentUser();
                router.goto('/');
            } else alert('Wrong email or password');
        } catch(e) {
            alert('Could not log in');
            console.log(e);
        }
    }

    const handleSignup = async () => {
        const signupData = new FormData();
        signupData.append('isAdmin', 0);
        signupData.append('name', name);
        signupData.append('surname', surname);
        signupData.append('email', email);
        signupData.append('password', password);
        try {
            const res = await fetch('/api/register', {
                method: 'POST',
                body: signupData
            });
            if(res.ok) {
                const user = await getCurrentUser();
                router.goto('/');
            }
            else throw new Error();
        } catch(e) {
            alert('Could not sign up');
            console.log(e);
            throw new Error(e);
        }
    }
</script>

<svelte:head>
    <title>Login | Bug Tracker</title>
</svelte:head>
{#if loaded===true}
<div class="login-container" in:fade={{delay: 200, duration: 1000}}>
    <div class="login" bind:clientWidth="{flyLength}">
        <div class="top-decor"></div>
        {#if mode==='login'}    
        <div class="login-wrapper" in:fly={{x:-flyLength, opacity: 100}} out:fly={{x:-flyLength, opacity: 100}}>
            <h1 class="header">Sign in</h1>
            <form method="POST" class="login-form" on:submit|preventDefault={handleLogin}>
                <EmailField bind:value={email} required={true} />
                <PasswordField bind:value={password} required={true} />
                <Submit label="Login" id="login-button" />
            </form>
            <div class="signup-question">Don't have an account yet? <span class="signup-button" on:click={() => mode = 'signup'}>Sign up</span></div>
        </div>
        {/if}
        {#if mode==='signup'}
        <div class="signup-wrapper" in:fly={{x:flyLength, opacity: 100}} out:fly={{x:flyLength, opacity: 100}}>
            <h1 class="header">Sign up</h1>
            <form method="POST" class="signup-form" on:submit|preventDefault={handleSignup}>
                <TextField label="Name" bind:value={name} required={true} />
                <TextField label="Surname" bind:value={surname} required={true} />
                <EmailField bind:value={email} required={true} />
                <PasswordField bind:value={password} required={true} />
                <Submit label="Sign up" id="signup-button" />
            </form>
            <div class="login-question">Already have an account? <span class="login-button" on:click={() => mode = 'login'}>Sign in</span></div>
        </div>
        {/if}
    </div>
</div>
{/if}

<style>
    .login-container {
        width: 100vw;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 10;
        background-color: var(--primary-dark);
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .login {
        position: relative;
        box-sizing: border-box;
        width: clamp(300px, 40%, 500px);
        height: clamp(500px, 55%, 800px);
        background-color: var(--primary-white);
        margin-top: 15vh;
        border-radius: 15px;
        overflow: hidden;
    }

    .login-wrapper {
        position: absolute;
        width: 100%;
        height: 100%;
        margin-left: 1rem;
        padding: 1rem 0.5rem;
        top: 0;
        left: 0;
    }

    .signup-wrapper {
        position: absolute;
        width: 100%;
        height: 100%;
        margin-left: 1rem;
        padding: 1rem 0.5rem;
        top: 0;
        left: 0;
    }

    .top-decor {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3rem;
        background-color: var(--primary-blue);
    }

    .header {
        width: 80%;
        margin: 0.25rem 0;
        margin-top: 2.5rem;
        padding: 5px;
    }

    .signup-question {
        font-weight: 500;
        margin-top: 0.5rem;
        margin-bottom: 0;
    }
    
    .login-question {
        font-weight: 500;
        margin-top: 0.5rem;
        margin-bottom: 0;
    }

    .signup-button {
        font-weight: 800;
        cursor: pointer;
    }
    
    .login-button {
        font-weight: 800;
        cursor: pointer;
    }

    @media only screen and (max-width: 600px) {
        .login {
            width: 80vw;
        }
    }
</style>