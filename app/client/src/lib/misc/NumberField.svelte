<script>
    export let label = 'Label';
    export let value = '';
    export let required = false;
    export let min = 0;
    export let max = 100;
</script>

<div class="form-text">
    <label for="{label}-form" class="form-number-label">{label}</label>
    <input  min={min} max={max} type="number" name="{label}-form" class="form-number-field" bind:value={value} required={required} />
</div>

<style>
    .form-text {
        box-sizing: border-box;
        position: relative;
        height: fit-content;
        width: 100%;
        padding: 5px;
        margin: 0.5rem 0.5rem;
        margin-left: 0rem;
    }

    .form-number-label {
        position: absolute;
        left: clamp(0.75rem, 5%, 1.5rem);
        font-size: 1rem;
        background-color: var(--primary-white);
    }

    .form-number-field {
        width: 80%;
        margin-top: 0.6rem;
        padding: 5px;
        border-radius: 5px;
        border: 2px solid var(--primary-blue);
        transition: 0.2s cubic-bezier(0.075, 0.82, 0.165, 1);
    }

    .form-number-field:focus {
        width: 80%;
        outline: none;
        border: 2px solid var(--secondary-yellow);
    }

    @media only screen and (max-width: 600px) {
        .form-text {            
            margin: 0.25rem 0.5rem;
            margin-left: 0;
        }
        
        .form-number-field {
            width: 90%;
        }

        .form-number-field:focus {
            width: 92.5%;
        }
    }
</style>