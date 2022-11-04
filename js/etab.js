$(function () {
    var objectProvider = new DevExpress.fileManagement.ObjectFileSystemProvider({
      data: fileSystem
    });
    var tooltipProvider = new DevExpress.fileManagement.CustomFileSystemProvider({
      getItems: function (parentItem) {
        const def = new $.Deferred();
        objectProvider.getItems(parentItem).then(function (items) {
          const tooltipedItems = items.map(function (item) {
            item.tooltipText = item.dataItem.tooltipText || "";
            return item;
          });
          def.resolve(tooltipedItems);
        });
        return def.promise();
      },
      renameItem: function (item, name) {
        return objectProvider.renameItem(item, name);
      },
      createDirectory: function (parentDir, name) {
        return objectProvider.createDirectory(parentDir, name);
      },
      deleteItem: function (item) {
        return objectProvider.deleteItems([item]);
      },
      moveItem: function (item, destinationDir) {
        return objectProvider.moveItems([item], destinationDir);
      },
      copyItem: function (item, destinationDir) {
        return objectProvider.copyItems([item], destinationDir);
      },
      downloadItems: function (items) {
        return objectProvider.downloadItems(items);
      },
      uploadFileChunk: function (fileData, chunksInfo, destinationDir) {
        return objectProvider.uploadFileChunk(
          fileData,
          chunksInfo,
          destinationDir
        );
      }
    });
    //++version 1
    // tooltipProvider._baseConvertDataObjectsToFileItems = tooltipProvider._convertDataObjectsToFileItems;
    // tooltipProvider._convertDataObjectsToFileItems = function() {
    // 	const items = tooltipProvider._baseConvertDataObjectsToFileItems(...arguments);
    // 	return items.map(function(item) {
    // 		item.tooltipText = item.dataItem.tooltipText || '';
    // 		return item;
    // 	});
    // }
    //--version 1
    //++version 2
    tooltipProvider._convertDataObjectsToFileItems = (dataItems) => dataItems;
    //--version 2
  


    
    $("#file-manager").dxFileManager({
      name: "fileManager",
      fileSystemProvider: tooltipProvider,
      currentPath: "Documents",
      height: 760,
      itemView: {
        mode: "thumbnails"
      },
      permissions: {
        create: true,
        copy: true,
        move: true,
        delete: true,
        rename: true,
        upload: true,
        download: true
      }
    });
  });
  
  var fileSystem = [
    {
      name: "Montgenevre",
      isDirectory: true,
      tooltipText: "Documents",
      items: [
        {
          name: "Projects",
          isDirectory: true,
          tooltipText: "Projects tooltip text",
          items: [
            {
              name: "About.rtf",
              isDirectory: false,
              size: 1024
            },
            {
              name: "Passwords.rtf",
              isDirectory: false,
              size: 2048
            }
          ]
        },
        {
          name: "About.xml",
          isDirectory: false,
          size: 1024,
          tooltipText: "File tooltip text"
        },
        {
          name: "Managers.rtf",
          isDirectory: false,
          size: 2048
        },
        {
          name: "ToDo.txt",
          isDirectory: false,
          size: 3072
        }
      ]
    },
    {
      name: "Flaine",
      isDirectory: true,
      items: [
        {
          name: "logo.png",
          isDirectory: false,
          size: 20480
        },
        {
          name: "banner.gif",
          isDirectory: false,
          size: 10240
        }
      ]
    },
    {
      name: "Isola",
      isDirectory: true,
      items: [
        {
          name: "Employees.txt",
          isDirectory: false,
          size: 3072
        },
        {
          name: "PasswordList.txt",
          isDirectory: false,
          size: 5120
        }
      ]
    },
    {
      name: "Description.rtf",
      isDirectory: false,
      size: 1024
    },
    {
      name: "Description.txt",
      isDirectory: false,
      size: 2048
    }
  ];
  



















  