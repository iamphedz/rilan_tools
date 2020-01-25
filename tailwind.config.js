module.exports = {
    theme: {
        container: {
            center: true,
            padding: "0.25rem"
        },
        extend: {
            colors: {
                "dark-green": "#143023",
                "ash-green": "#fafffc"
            },
            spacing: {
                "36": "9rem",
                "14": "3.5rem",
                "13": "3.25rem",
                "68": "17rem",
                "128": "32rem"
            },
            opacity: {
                "10": ".1",
                "20": ".2",
                "30": ".3",
                "40": ".4",
                "50": ".5",
                "60": ".6",
                "70": ".7",
                "80": ".8",
                "90": ".9",
                "100": "1"
            },
            borderWidth: {
                "6": "6px",
                "8": "8px",
                "10": "10px",
                "12": "12px"
            },
            margin: {
                "-14": "-3.5rem",
                "9": "2.25rem",
                "7": "1.75rem",
                "7h": "2rem"
            }
        }
    },
    variants: {
        borderColor: ["responsive", "hover", "focus", "focus-within"],
        width: ["responsive", "hover", "focus"],
        backgroundColor: [
            "responsive",
            "hover",
            "focus",
            "active",
            "focus-within"
        ]
    },
    plugins: []
};
