import { useState } from "react";
import axios from "axios";

const useApi = (baseURL) => {
    const [data, setData] = useState(null);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);

    const apiCall = async (method, endpoint, body = null, headers = {}) => {
        setLoading(true);
        setError(null);

        try {
            // Si un token est présent dans headers, on l'ajoute au headers
            const token = localStorage.getItem("token"); // Ou récupérer depuis un autre endroit
            if (token) {
                headers.Authorization = `Bearer ${token}`;
            }

            // console.log('token token'+token)

            const response = await axios({
                method,
                url: `${baseURL}${endpoint}`,
                data: body,
                headers,
            });

            setData(response.data);
            return response.data;
        } catch (err) {
            setError(err.response ? err.response.data.error : "Erreur inconnue");
            return err;
        } finally {
            setLoading(false);
        }
    };

    return { data, loading, error, apiCall };
};

export default useApi;
