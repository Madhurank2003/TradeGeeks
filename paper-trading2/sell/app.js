// Function to fetch user's portfolio
const fetchUserPortfolio = async () => {
    try {
        const response = await fetch('../get-user-stocks.php');
        const data = await response.json();
        if (data.success) {
            const userPortfolio = data.stocks;
            renderPortfolio(userPortfolio);
        } else {
            console.error(`Error: ${data.message}`);
        }
    } catch (error) {
        console.error('Error fetching user portfolio:', error);
    }
};

// Function to fetch real-time stock price
const fetchStockPrice = async (symbol) => {
    try {
        const response = await fetch(`https://cloud.iexapis.com/stable/stock/${symbol}/quote?token=pk_7f874d5cdec94dc4873f420667ce920d`);
        const data = await response.json();
        return data.latestPrice;
    } catch (error) {
        console.error(`Error fetching stock price for ${symbol}:`, error);
        return null;
    }
};

// Function to render user's portfolio
const renderPortfolio = async (portfolio) => {
    const portfolioContainer = document.getElementById('portfolio-container');
    // Clear existing content
    portfolioContainer.innerHTML = '';
    if (portfolio && Array.isArray(portfolio)) {
        if (portfolio.length > 0) {
            for (const stock of portfolio) {
                const currentPrice = await fetchStockPrice(stock.stock_symbol);
                const stockElement = document.createElement('div');
                stockElement.className = 'stock-card';
                stockElement.dataset.symbol = stock.stock_symbol;
                stockElement.dataset.quantity = stock.net_shares;
                stockElement.innerHTML = `
                    <p>${stock.stock_symbol} - Net Quantity: ${stock.net_shares} - Current Price: $${currentPrice}</p>
                    <button class="sell-button" onclick="confirmSell('${stock.stock_symbol}', '${stock.net_shares}','${currentPrice}')">Sell</button>
                `;
                portfolioContainer.appendChild(stockElement);
            };
        } else {
            console.error('Portfolio is empty.');
        }
    } else {
        console.error('Invalid or empty portfolio data:', portfolio);
    }
};

// Function to confirm the sell action with a modal
const confirmSell = (symbol, netQuantity, currentPrice) => {
    const modal = document.getElementById('sell-modal');
    modal.style.display = 'block';
    // Store stock details in modal for later use
    modal.symbol = symbol;
    modal.netQuantity = netQuantity;
    modal.currPrice = currentPrice;
};

// Function to close the modal
const closeModal = () => {
    const modal = document.getElementById('sell-modal');
    modal.style.display = 'none';
};

// Function to handle the sell action
const sellStock = () => {
    const quantityInput = document.getElementById('quantityInput');
    const sellQuantity = parseInt(quantityInput.value, 10);

    if (!isNaN(sellQuantity) && sellQuantity > 0) {
        // Get stock details from modal
        const symbol = document.getElementById('sell-modal').symbol;
        const netQuantity = document.getElementById('sell-modal').netQuantity;
        const currPrice = document.getElementById('sell-modal').currPrice;
        if (sellQuantity <= netQuantity) {

            fetch('../sell-process.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    'stock_symbol': symbol,
                    'sell_quantity': sellQuantity,
                    'sell_price': currPrice
                }),
            })
                .then(response => {
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert("Transaction Successfull");
                    }
                    else {
                        alert("Failed Transaction");
                    }
                })
            fetchUserPortfolio();
            closeModal();
        } else {
            alert('Quantity Greater than the current holdings.');
        }
    } else {
        alert('Please enter a valid quantity.');
    }
};
// Render user's portfolio when the page loads
document.addEventListener('DOMContentLoaded', fetchUserPortfolio);
