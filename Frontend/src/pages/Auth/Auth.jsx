import { useEffect, useState } from "react";
import { useNavigate } from 'react-router-dom';
import useApi from "../../hooks/useApi";
import useAuth from "../../hooks/useAuth";


function Auth () {
    const navigate = useNavigate();
    const [isLogin, setIsLogin] = useState(true);
    const { loading, error, apiCall } = useApi("http://127.0.0.1:8000/api");
    const { storeDate, getStore } = useAuth();



    useEffect(() => {
        if (getStore().user) {
            navigate('/transactions'); 
          }
    },[]);

    const handleSubmit = async (e) => {
        e.preventDefault();

        const newData = {
            password: e.target.password.value,
            email: e.target.email.value,
            name: e.target.name.value,
        };
    
        if(isLogin) {
            console.log('login')
            const response = await apiCall("POST", "/login", newData);
            if (response) {
                console.log("Connexion réussie :", response.data);
                storeDate(response.data.user, response.data.token);
                 navigate('/transactions'); 
            }
        } else {
          console.log('log')
            const response = await apiCall("POST", "/register", newData);
            if (response) {
                console.log("Connexion réussie :", response.data);
                storeDate(response.data.user, response.data.token);
                 navigate('/transactions'); 
            }
        }



        console.log(newData)
    }

    return (
        <div className="flex min-h-screen items-center justify-center bg-gray-100 dark:bg-gray-900 p-6">
        <div className="w-full max-w-md space-y-6 bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
          <h2 className="text-2xl font-semibold text-gray-900 dark:text-white text-center">
            {isLogin ? "Se connecter" : "Créer un compte"}
          </h2>
  
          <form onSubmit={handleSubmit} className="space-y-4">
            {!isLogin && (
              <input
                type="text"
                placeholder="Nom complet"
                name="name"
                className="w-full px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            )}
  
            <input
              type="email"
              placeholder="Email"
              name="email"
              className="w-full px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
  
            <input
              type="password"
              name="password"
              placeholder="Mot de passe"
              className="w-full px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
  
            <button className="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-lg transition">
                {loading ? "Connexion..." : isLogin ? "Créer un compte" : "Se connecter"}
            </button>
          </form>
  
          <p className="text-center text-gray-600 dark:text-gray-400">
            {isLogin ? "Pas encore de compte ?" : "Déjà un compte ?"}
            <button
              className="ml-2 text-blue-600 hover:underline"
              type="submit"
              onClick={() => setIsLogin(!isLogin)}
            >
               {isLogin ? "Créer un compte" : "Se connecter"} 
            </button>
          </p>
        </div>
      </div>
    );
}

export default Auth;