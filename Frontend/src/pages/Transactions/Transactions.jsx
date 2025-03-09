import React, { useEffect, useState } from 'react';
import { FaPlus } from 'react-icons/fa';
import useApi from '../../hooks/useApi';
import { useNavigate } from 'react-router-dom';


function Transactions() {
  const navigate = useNavigate();
  const user = JSON.parse(localStorage.getItem("user"));

  const { loading, error, apiCall } = useApi("http://127.0.0.1:8000/api");
  const [transactions, setTransactions] = useState([]);
  const [lastPage, setLastPage] = useState(1);
  const [actualPage, setActualPage] = useState(1);
  const [amount, setAmount] = useState(0);

  const [filters, setFilters] = useState({
    type: '',
    date: ''
  });


  useEffect(() => {
    fetchTransactions();
  }, []);

  useEffect(() => {
    console.log(transactions)
  }, [transactions]);

  const fetchTransactions = async () => {
    const response = await apiCall("GET", "/transactions");
    setTransactions(response.data);
    setLastPage(response.meta.last_page);

    //get amount
    const responseUser = await apiCall("GET", "/user");
    setAmount(responseUser.amount)
  };

  const pages = [...Array(lastPage)].map((_, index) => index + 1);



  const goToPage = async (page) => {
    let query = `/transactions?page=${page}&`;
    const response = await apiCall("GET", query);
    setTransactions(response.data);
    setActualPage(page);

    
  };

  const handleSearch = async () => {
    let query = `/transactions?`;
  
    if (filters.type) query += `type=${filters.type}&`;
  
    if (filters.date) {
      // Formater la date au format 'YYYY-MM-DD'
      const formattedDate = new Date(filters.date).toISOString().split('T')[0];
      query += `date=${formattedDate}`;
    }
  
    const response = await apiCall("GET", query);
    setTransactions(response.data);
  };

  // Extraire les initiales du nom
  const initials = user.name
    .split(" ")
    .map((word) => word.charAt(0).toUpperCase())
    .join("");

  return (
    <div className="flex flex-col items-center justify-center min-h-screen bg-gray-50 dark:bg-gray-900 p-6">
      {/* Section avec 3 div alignées horizontalement */}
      <div className="flex w-full max-w-lg space-x-4 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
        <div className="flex items-center justify-center space-x-4 w-1/3 bg-gray-100 dark:bg-gray-700 p-4 rounded-xl shadow-md">
          <div className="flex items-center justify-center w-16 h-16 bg-blue-600 text-white font-bold rounded-full text-2xl">
            {initials}
          </div>
        </div>

        <div className="w-1/3 bg-gray-100 dark:bg-gray-700 p-4 rounded-xl shadow-md">
          <h3 className="text-sm text-gray-600 dark:text-gray-400 mb-2">
            Solde actuel
          </h3>
          <p className="text-2xl font-bold text-blue-600 dark:text-blue-400">
            ${amount}
          </p>
        </div>

        <div className="w-1/3 bg-gray-100 dark:bg-gray-700 p-4 rounded-xl shadow-md flex items-center justify-center">
          <button className="flex items-center justify-center w-10 h-10 bg-blue-600 text-white font-bold rounded-full hover:bg-blue-700 transition" onClick={() => { navigate('/create_transaction')}}>
            <FaPlus className="text-xl" />
          </button>
        </div>
      </div>

      {/* Barre de recherche */}
      <div className="flex space-x-4 bg-white dark:bg-gray-800 p-4 rounded-xl shadow-md w-full max-w-4xl my-6">
        {/* Filtre Type */}
        <select
          value={filters.type}
          onChange={(e) => setFilters({ ...filters, type: e.target.value })}
          className="p-2 border rounded-lg w-1/3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300"
        >
          <option value="">Tous</option>
          <option value="transfer">Transfer</option>
          <option value="deposit">Deposit</option>
          <option value="withdrawal">Withdrawal</option>
        </select>

        {/* Filtre Date */}
        <input
          type="date"
          value={filters.date}
          onChange={(e) => setFilters({ ...filters, date: e.target.value })}
          className="p-2 border rounded-lg w-1/3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300"
        />

        {/* Bouton Recherche */}
        <button
          onClick={handleSearch}
          className="w-1/3 bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700 transition"
        >
          {loading ? '...' : 'Recherche'}
        </button>
      </div>

      {/* Tableau des transactions */}
      <div className="overflow-x-auto p-4 w-full max-w-4xl">
        <table className="min-w-full bg-white dark:bg-gray-800 shadow-md rounded-lg">
          <thead>
            <tr className="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
              <th className="py-2 px-4 text-left">ID</th>
              <th className="py-2 px-4 text-left">Type</th>
              <th className="py-2 px-4 text-left">Expéditeur</th>
              <th className="py-2 px-4 text-left">Montant</th>
              <th className="py-2 px-4 text-left">Date du transfert</th>
            </tr>
          </thead>
          <tbody>
            {transactions.length > 0 ? (
              transactions.map((transaction) => (
                <tr
                  key={transaction.id}
                  className="border-b dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600"
                >
                  <td className="py-2 px-4">{transaction.id}</td>
                  <td className="py-2 px-4 capitalize">{transaction.type}</td>
                  <td className="py-2 px-4">
                    {transaction.receiver ? transaction.receiver.name : ""}
                  </td>
                  <td className="py-2 px-4 text-blue-600 font-semibold">
                    ${transaction.amount}
                  </td>
                  <td className="py-2 px-4">
                    {transaction.transferred_at
                      ? new Date(transaction.transferred_at).toLocaleDateString()
                      : "Non défini"}
                  </td>
                </tr>
              ))
            ) : (
              <tr>
                <td colSpan="5" className="text-center py-4 text-gray-500">
                  Aucune transaction trouvée.
                </td>
              </tr>
            )}
          </tbody>
        </table>
      </div>

      {/*composant de pagination*/} 

      <div className="flex justify-center space-x-2 mt-4">
      {pages.map((page) => (
        <button
          key={page}
          onClick={() => goToPage(page)}
          className={`px-4 py-2 rounded-md ${page === actualPage ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-blue-500 hover:text-white'}`}
        >
          {page}
        </button>
      ))}
    </div>

    </div>
  );
}

export default Transactions;
