import { useState, useEffect } from "react";

const useAuth = () => {
    const [user, setUser] = useState(null);
    const [token, setToken] = useState(null);

    // 🔹 Lire depuis localStorage au chargement
    useEffect(() => {
        const storedUser = localStorage.getItem("user");
        const storedToken = localStorage.getItem("token");

        if (storedUser && storedToken) {
            setUser(JSON.parse(storedUser));
            setToken(storedToken);
        }
    }, []);

    const getStore = () => {
        const storedUser = localStorage.getItem("user");
        const storedToken = localStorage.getItem("token");

        if (storedUser && storedToken) {
            setUser(JSON.parse(storedUser));
            setToken(storedToken);
        }

        return {"user": JSON.parse(storedUser), "token": storedToken};
    }

    // 🔹 Fonction pour sauvegarder l'utilisateur après connexion
    const storeDate = (userData, authToken) => {
        setUser(userData);
        console.log(user)
        setToken(authToken);

        localStorage.setItem("user", JSON.stringify(userData));
        localStorage.setItem("token", authToken);
    };

    // 🔹 Déconnexion : Supprime les données
    const logout = () => {
        setUser(null);
        setToken(null);

        localStorage.removeItem("user");
        localStorage.removeItem("token");
    };

    return { user, token, storeDate, getStore, logout };
};

export default useAuth;
