import React, { useState, useEffect } from "react";

export default function ThemeMode() {
    const [themes] = useState(["warm", "light", "dark"]);
    const [currentThemeIndex, setCurrentThemeIndex] = useState(() => {
        const savedTheme = localStorage.getItem("currentThemeIndex");
        return savedTheme ? JSON.parse(savedTheme) : 0;
    });

    useEffect(() => {
        document.body.className = themes[currentThemeIndex];
        localStorage.setItem(
            "currentThemeIndex",
            JSON.stringify(currentThemeIndex)
        );
    }, [currentThemeIndex, themes]);

    const toggleTheme = () => {
        const nextIndex = (currentThemeIndex + 1) % themes.length;
        setCurrentThemeIndex(nextIndex);
    };

    return (
        <div>
            <button onClick={toggleTheme} className="btn transparent">
                <img
                    src={`/assets/images/${themes[currentThemeIndex]}.png`}
                    alt={`${themes[currentThemeIndex]} Theme`}
                    style={{ height: "30px" }}
                />
            </button>
        </div>
    );
}
