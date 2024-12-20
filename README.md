# Library Web Portal

This is a web portal for managing and accessing library books. It provides features for both library members and administrators.

### ERD

  [ERD.pdf](https://github.com/user-attachments/files/18143663/ERD.pdf)
  ![ERD](https://github.com/user-attachments/assets/c117891a-1d8f-44f2-965f-35b1255aeece)

## Features

### Select a book from the table to see detailed information
  
  ![select_book](https://github.com/derrickadkins/library/assets/11668198/f7c46363-f18d-473a-bc90-54e0b125c5cd)

### Instantly check a book in or out
  
  ![check_book](https://github.com/derrickadkins/library/assets/11668198/5a5c39f0-aa70-4b1e-a7f6-5c0dfa960786)

### Search and sort tables
  
  ![search_table](https://github.com/derrickadkins/library/assets/11668198/ce306da2-7497-4dad-ab90-8948717de705)

### Update your profile information
  
  ![view_update_profile](https://github.com/derrickadkins/library/assets/11668198/ce963b20-5ef2-4cbb-b009-71ff9e0eaf01)

### Update passwords to keep accounts secure
  
  ![update_password](https://github.com/derrickadkins/library/assets/11668198/7a4095fd-d813-4cba-98de-b6d68e99cc30)
  
### Secure Authentication
  
  ![login](https://github.com/derrickadkins/library/assets/11668198/ccac5965-d553-4ff0-943c-13061d5ca77e)

### Forms use regex to validate input
  
  ![regex_validation](https://github.com/derrickadkins/library/assets/11668198/eb35dddd-b2c9-4ba2-9174-b5321ea49c48)

### Admin role grants permission to add, update, and delete books and members
  
  ![delete_book](https://github.com/derrickadkins/library/assets/11668198/84ede89c-45d8-4e78-bcae-6080efb7e7cb)

### Admins can download a report of all currently checked out books

  ![download_report](https://github.com/derrickadkins/library/assets/11668198/8192507d-ed0e-49ac-a44b-5210a78f8434)

### Dynamic pages provide context based actions

| Admin view | Member view |
|:----------:|:-----------:|
| <img src="https://github.com/derrickadkins/library/assets/11668198/03c701f2-7cfd-4916-a9b3-ad80c6f960bf" style="width: 100%;"/> | <img src="https://github.com/derrickadkins/library/assets/11668198/d0b4ce4e-446d-42df-8a24-671d4e372f60" style="width: 70%;"/> |

### For Members

- Browse available books
- Check a book in and out
- View currently borrowed books
- See who a book is checked out by and when they checked it out
- Update profile and password

### For Administrators

- Add new books to the library
- Remove books from the library
- Update books in the library
- View all currently borrowed books
- Create new profiles
- Update existing profiles
- Delete other profiles
- Download checked out report

## Getting Started

To get a local copy up and running, follow these steps:

1. Clone the repository
```
git clone https://github.com/derrickadkins/library.git
```
2. Install dependencies
```
npm install
```
3. Start the server
```
npm start
```

## Usage

Open your web browser and visit `http://localhost:3000` to start using the library web portal.

## Contributing

Contributions are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
