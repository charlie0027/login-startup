import {
    ref,
    onMounted
} from 'vue'

const isDark = ref(true)

export function useTheme() {
    function applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme)
        localStorage.setItem('theme', theme)
        isDark.value = theme === 'dark'
    }

    function toggleTheme() {
        applyTheme(isDark.value ? 'light' : 'dark')
    }

    onMounted(() => {
        const saved = localStorage.getItem('theme') || 'dark'
        applyTheme(saved)
    })

    return {
        isDark,
        toggleTheme
    }
}
