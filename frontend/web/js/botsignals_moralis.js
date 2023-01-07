Moralis.initialize("z5oYGgC3S8aCXxbBkZSJ7rzlwOQz5Tt7FgzCVFWv"); // Botsignals Rinkeby testnet 7okt21 test
Moralis.serverURL = "https://kjzl4puf1lpi.moralishost.com:2053/server";

/* -- user suthenticate -- */
async function moralis_signup(username, email, password) {
  try {
	  console.log('moralis_signup: u='+username+' e='+email+' p='+password);
    const user = new Moralis.User();
    user.set("username", username);
    user.set("email", email);
    user.set("password", password);
    await user.signUp();
    //console.log('moralis_signup: Ok');
    return {'code':200, 'messagë':'OK'};
  } catch (error) {
    console.error('moralis_signup: '+error.code+'='+error.message);
    return {'code':error.code, 'message':error.message};
  }
}

function moralis_getUserDetails() {
	const user = Moralis.User.current();
  if (user) {
    return {
      'uid':user.get('uuid'),
      'username':user.get('username'),
      'email':user.get('email'),
    }
  } else {
    return {}
  }
}

async function logOut() {
  console.log("logged out. User:", Moralis.User.current());
  await Moralis.User.logOut();
}

async function loginWithMetaMask() {
  let user = Moralis.User.current();
  if (!user) {
    user = await Moralis.Web3.authenticate();
  }
  console.log(user);
}

async function loginWithUsernamePawword() {
  const username = document.getElementById("loginform-username").value;
  const pass = document.getElementById("loginform-password").value;

  if (!username || !pass) {
    console.log("MOR-loginWithUsernamePawword: please provide both username and password");
    return;
  }

  try {
    await Moralis.User.logIn(username, pass);
  } catch (error) {
    console.log('MOR-loginWithUsernamePawword: '+error);
    //alert("invalid username or password");
  }
}

function listenForAccountChange() {
  Moralis.Web3.onAccountsChanged(async function (accounts) {
    console.log('MOR-listenForAccountChange: account changed: ', accounts);
    const user = Moralis.User.current();
    if (!user || !accounts.length) {
      console.log('MOR-listenForAccountChange: not logged in');
    } else {
      try {
        const address = accounts[0];
        if (addressAlreadyLinked(user, address)) {
          console.log(`MOR-listenForAccountChange: address ${getAddressTxt(address)} already linked`);
        } else {
          const confirmed = confirm("Link this address to your account?");
          if (confirmed) {
            await Moralis.Web3.link(address);
            alert("Address added!");
            //render('profile');
          }
        }
      } catch (error) {
        if (error.message.includes("already used")) {
          alert("That address is already linked to another profile!");
        } else {
          console.error('MOR-listenForAccountChange: '+error);
          alert("Error while linking. See the console.");
        }
      }
    }
  });
}

function addressAlreadyLinked(user, address) {
  return (
    user &&
    address &&
    user.attributes.accounts &&
    user.attributes.accounts.includes(address)
  );
}

async function onUnlinkAddress(event) {
  event.preventDefault();
  try {
    const address = event.target.dataset.addr;
    console.log('MOR-onUnlinkAddress: addr:', address);

    const confirmed = confirm("Are you sure you want to remove this address?");
    if (confirmed) {
      await Moralis.Web3.unlink(address);
      alert("Address removed from profile!");
      //render('profile');
    }
  } catch (error) {
    console.error('MOR-addressAlreadyLinked: '+error);
    alert("Error unlinking address. See console.");
  }
}

async function onSaveNewPassword(event) {
  event.preventDefault();
  const user = Moralis.User.current();

  try {
    const newPass = document.getElementById("password").value;
    user.setPassword(newPass);
    await user.save();
    console.log("MOR-onSaveNewPassword: Password updated successfully!");
  } catch (error) {
    console.error('MOR-onSaveNewPassword: '+error);
    //alert("Error while saving new password. See the console");
  }
}

/* -- user profile -- */
function buildAddrListComponent(user) {
  // add each address to the list
  let addressItems = "";
  if (user.attributes.accounts && user.attributes.accounts.length) {
    addressItems = user.attributes.accounts
      .map(function (account) {
        return `<li>
          <button class="btn-addr btn-remove" type="button" data-addr="${account}">X</button>
          ${getAddressTxt(account)}
        </li>`;
      })
      .join("");
  } else {
    // no linked addreses, add button to link new address
    addressItems = `
    <li>
      <button class="btn-addr" type="button" id="btn-add-addr">+</button>
      Link
    </li>
    `;
  }

  return `
    <div>
      <h3>Linked Addresses</h3>
      <ul>
        ${addressItems}
      </ul>
    </div>
  `;
}

function getAddressTxt(address) {
  return `${address.substr(0, 5)}...${address.substr(
    address.length - 5,
    address.length
  )}`;
}

function renderProfile(user) {
  const btnAddAddress = document.getElementById("btn-add-addr");
  if (btnAddAddress) {
    btnAddAddress.onclick = onAddAddress;
  }
}

async function onAddAddress() {
  try {
    // enabling web3 will cause an account changed event
    // which is already subscribed to link on change so
    // just connecting Metamask will do what we want
    // (as long as the account is not currently connected)
    await Moralis.Web3.enable();
  } catch (error) {
    console.error('MOR-onAddAddress: '+error);
    alert("Error while linking new address. See console");
  }
}

async function onSaveProfile(event) {
  event.preventDefault();
  const user = Moralis.User.current();

  try {
    // get values from the form
    const username = document.getElementById("name").value;
    const bio = document.getElementById("bio").value;
    console.log("username:", username, "bio:", bio);

    // update user object
    user.setUsername(username); // built in
    user.set("bio", bio); // custom attribute

    await user.save();
    alert("saved successfully!");
    doEndProfile();
  } catch (error) {
    console.error(error);
    alert("Error while saving. See the console.");
  }
}

/* -- end -- */
