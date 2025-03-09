import React, { useState, useEffect } from 'react';
import useApi from '../../hooks/useApi';  // Hook pour gérer les requêtes API

const SearchSelect = ({ onSelect }) => {
  const [search, setSearch] = useState(''); // État pour le texte de recherche
  const [users, setUsers] = useState([]); // Liste de tous les utilisateurs
  const [filteredUsers, setFilteredUsers] = useState([]); // Utilisateurs filtrés
  const [selectedUser, setSelectedUser] = useState(null); // Utilisateur sélectionné

  const { apiCall } = useApi('http://127.0.0.1:8000/api');  // Hook pour effectuer des appels API

  // Charger les utilisateurs au premier chargement du composant
  useEffect(() => {
    const fetchUsers = async () => {
      try {
        const response = await apiCall('GET', '/users');
        setUsers(response.data);  // Mettre à jour la liste des utilisateurs
        setFilteredUsers(response.data); // Afficher tous les utilisateurs par défaut
      } catch (error) {
        console.error('Erreur lors de la récupération des utilisateurs:', error);
      }
    };

    fetchUsers();
  }, []);

  // Filtrer les utilisateurs en fonction du texte de recherche
  const handleSearch = (event) => {
    const query = event.target.value;
    setSearch(query);

    // Filtrer les utilisateurs selon la recherche
    if (query) {
      const results = users.filter(user =>
        user.name.toLowerCase().includes(query.toLowerCase())
      );
      setFilteredUsers(results);
    } else {
      setFilteredUsers(users); // Réinitialiser à tous les utilisateurs si la recherche est vide
    }
  };

  // Gérer la sélection d'un utilisateur
  const handleSelect = (user) => {
    setSelectedUser(user);
    setSearch(user.name);  // Afficher le nom de l'utilisateur sélectionné dans le champ de recherche
    onSelect(user.id);  // Appeler la fonction de rappel avec l'ID de l'utilisateur sélectionné
  };

  return (
    <div className="relative">
      <input
        type="text"
        value={search}
        onChange={handleSearch}
        className="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-white"
        placeholder="Rechercher un utilisateur"
      />
      {filteredUsers.length > 0 && (
        <ul className="absolute w-full mt-1 bg-white border border-gray-300 rounded-lg max-h-48 overflow-y-auto z-10">
          {filteredUsers.map(user => (
            <li
              key={user.id}
              onClick={() => handleSelect(user)}
              className="p-2 cursor-pointer hover:bg-gray-100"
            >
              {user.name}
            </li>
          ))}
        </ul>
      )}
    </div>
  );
};

const CreateTransaction = () => {
  const [receiverId, setReceiverId] = useState('');
  const [amount, setAmount] = useState('');
  const [type, setType] = useState('transfer');
  const [err, setError] = useState('');
  const [errorMessage, setErrorMessage] = useState(''); // Gérer le message d'erreur API

  // Hook pour effectuer des appels API
  const {error, loading, apiCall } = useApi('http://127.0.0.1:8000/api');

  useEffect(() => {
    setErrorMessage(error);
    setError(true);
  },[error])


  // Fonction pour gérer la soumission du formulaire
  const handleSubmit = async (e) => {
    e.preventDefault();

    if (!amount || !type) {
      setErrorMessage('Tous les champs doivent être remplis.');
      setError(true);
      return;
    }

    if (!receiverId && type == 'transfer') {
        setErrorMessage('Selectioner le destinataire.');
        setError(true);
        return;
      }
      
    

 

    const response = await apiCall('POST', '/transactions', {
        receiver_id: receiverId,
        amount,
        type,
    });

          console.log(response.status);
    if (response.status != 401) {
        setReceiverId('');
        setAmount('');
        setType('transfer');
        setError(false);
        setErrorMessage('');
        alert('Transaction créée avec succès !');
      }
  };

  return (
    <div className="flex flex-col items-center justify-center min-h-screen bg-gray-50 dark:bg-gray-900 p-6">
      <div className="w-full max-w-lg bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
        <h2 className="text-xl font-bold text-gray-800 dark:text-white mb-4">Créer une Transaction</h2>

        {err && errorMessage && (
          <div className="mb-4">
            <div className="bg-red-100 text-red-700 border border-red-400 p-3 rounded-lg">
              {errorMessage}
            </div>
          </div>
        )}

        <form onSubmit={handleSubmit}>
          <div className="mb-4">
            <label htmlFor="receiverId" className="block text-gray-700 dark:text-gray-300 mb-2">
              Sélectionner un utilisateur destinataire
            </label>
            <SearchSelect onSelect={setReceiverId} />
          </div>

          <div className="mb-4">
            <label htmlFor="amount" className="block text-gray-700 dark:text-gray-300 mb-2">
              Montant
            </label>
            <input
              id="amount"
              type="number"
              value={amount}
              onChange={(e) => setAmount(e.target.value)}
              className="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-white"
              required
            />
          </div>

          <div className="mb-4">
            <label htmlFor="type" className="block text-gray-700 dark:text-gray-300 mb-2">
              Type de Transaction
            </label>
            <select
              id="type"
              value={type}
              onChange={(e) => setType(e.target.value)}
              className="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-black"
            >
              <option value="transfer">Transfert</option>
              <option value="deposit">Dépôt</option>
              <option value="withdrawal">Retrait</option>
            </select>
          </div>

          <button
            type="submit"
            className="w-full bg-blue-600 text-white font-bold py-2 rounded-lg hover:bg-blue-700 transition"
          >
            {loading ? 'En cour de traitement ...' : 'Créer la Transaction'} 
          </button>
        </form>
      </div>
    </div>
  );
};

export default CreateTransaction;
