// Function to fetch stock data from IEX Cloud
const fetchStockData = async () => {
    try {
        const response = await fetch(`https://cloud.iexapis.com/stable/stock/market/batch?symbols=AAPL,GOOGL,AMZN,MSFT,TSLA,FB,NVDA,PYPL,NFLX,V,DIS,BABA,GS,IBM,CSCO,AMD,INTC,COST,PG,WMT&types=quote&token=pk_7f874d5cdec94dc4873f420667ce920d`);
        const data = await response.json();

        const stocks = Object.keys(data).map(symbol => {
            const stockData = data[symbol].quote;
            return {
                symbol: symbol,
                name: stockData.companyName,
                price: stockData.latestPrice,
            };
        });

        renderStocks(stocks);
    } catch (error) {
        console.error('Error fetching stock data:', error);
    }
};

// Function to render stocks on the page
const renderStocks = (stocks) => {
    const stocksContainer = document.getElementById('stocks-container');

    stocks.forEach(stock => {
        const stockElement = document.createElement('div');
        stockElement.className = 'stock-card';
        stockElement.dataset.symbol = stock.symbol;
        stockElement.dataset.price = stock.price.toFixed(2);
        stockElement.innerHTML = `
            <p>${stock.name} (${stock.symbol}) - $${stock.price.toFixed(2)}</p>
            <button class="buy-button" onclick="confirmBuy('${stock.symbol}', ${stock.price})">Buy</button>
        `;
        stocksContainer.appendChild(stockElement);
    });
};


// Function to confirm the buy action with a modal
const confirmBuy = (symbol, price) => {
    const modal = document.getElementById('buy-modal');
    modal.style.display = 'block';
    // Store stock details in modal for later use
    modal.symbol = symbol;
    modal.price = price;
};

// Function to close the modal
const closeModal = () => {
    const modal = document.getElementById('buy-modal');
    modal.style.display = 'none';
};

const showModal = (modalId) => {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'block';
    }
};

// Function to hide a modal
const hideModal = (modalId) => {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
    }
};

// Function to handle the buy action
const buyStock = async () => {
    const amountInput = document.getElementById('amountInput');
    const amount = parseInt(amountInput.value, 10);

    if (!isNaN(amount) && amount > 0) {
        // Get stock details from modal
        const symbol = document.getElementById('buy-modal').symbol;
        const price = document.getElementById('buy-modal').price;

        // Use the amount, symbol, and price as needed (e.g., make a server request)
        const totalCost = amount * price;
        // alert(`Buying ${amount} ${symbol} stocks at $${price.toFixed(2)} each.\nTotal Cost : $${totalCost.toFixed(2)}\nAvailable : $${userAmount}`);
        fetch('../buyProcess.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                'currCost': totalCost,
                'currStock': symbol,
                'quantity': amount,
                'perShare': price
            }),
        })
            .then(response => {
                console.log(response);
                return response.json();
            })
            .then(data => {
                if (data.status == "success") {
                    alert("Transaction Successfull");
                    //showModal('success-modal');
                }
                else {
                    alert("Failed Transaction");
                    //showModal('failure-modal');
                }
            })

        // Close the modal after the buy action
        closeModal();
    } else {
        alert('Please enter a valid amount.');
    }
};

// Render stocks when the page loads
document.addEventListener('DOMContentLoaded', fetchStockData);
